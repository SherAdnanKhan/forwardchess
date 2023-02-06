<?php

use App\Models\Product\Product;
use App\Models\Product\Publisher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products_categories')->truncate();
        DB::table('coupons_products')->truncate();
        DB::table('publishers')->truncate();
        DB::table('products')->truncate();
        DB::table('coupons')->truncate();

        $publishers       = [];
        $publishersImages = [
            'brand-01.jpg',
            'brand-02.jpg',
            'brand-03.jpg',
            'brand-04.jpg'
        ];

        $results = DB::table('__old_fc_posts')->where('post_type', '=', 'product')->get();

        foreach ($results as $oneResult) {
            $meta = DB::table('__old_fc_postmeta')->where('post_id', '=', $oneResult->ID)->get();

            $metaColumns = [
                'total_sales'        => 'totalSales',
                '_regular_price'     => 'price',
                '_price'             => 'discountPrice',
                '_sku'               => 'sku',
                '_book_publish_date' => 'publishDate',
                '_book_content'      => 'nrPages',
                '_book_author'       => 'author',
                '_book_publisher'    => 'publisher'
            ];

            $attributes = [];
            foreach ($meta as $item) {
                if (array_key_exists($item->meta_key, $metaColumns)) {
                    $attributes[$metaColumns[$item->meta_key]] = $item->meta_value;
                }
            }

            if (!count($attributes)) {
                continue;
            }

            $product = array_merge([
                'created_at'  => $oneResult->post_date,
                'title'       => $oneResult->post_title,
                'description' => $oneResult->post_content,
                'url'         => $oneResult->post_name,
                'lastId'      => $oneResult->ID,
            ], (array)$attributes);

            $products[] = $product;

            $publisherName = snake_case(strtolower($product['publisher']));

            if (isset($product['publisher']) && !isset($publishers[$publisherName])) {
                $publisher = Publisher::create([
                    'name' => $product['publisher'],
                    'logo' => $publishersImages[rand(0, 3)]
                ]);

                $publishers[$publisherName] = $publisher;
            }

            $product['publisherId'] = $publishers[$publisherName]->id;
            $product['active']      = 1;
            unset($product['publisher']);

            if ($product['price'] === $product['discountPrice']) {
                $product['discountPrice'] = 0;
            }

            $publishDate = isset($product['publishDate']) ? $product['publishDate'] : null;
            unset($product['publishDate']);

            if (empty($product['sku'])) {
                $product['sku'] = $product['url'];
            }

            $productModel              = Product::create($product);
            $productModel->publishDate = $this->getPublishDate($productModel, $publishDate);
            $productModel->save();
        }

//        $this->importImages();
    }

    private function getPublishDate(Product $product, $date)
    {
        if (empty($date)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $product->created_at);
        } else {
            $specialValues = [
                'October, 2016'  => 'October 2016',
                'November 20147' => 'November 2014',
            ];

            if (array_key_exists($date, $specialValues)) {
                $date = $specialValues[$date];
            }

            if (preg_match('/,/', $date)) {
                $publishDate = Carbon::createFromFormat('M d, Y H:i', $date . ' 00:00');
            } else {
                list($month, $year) = explode(' ', $date);
                $seasons = [
                    'Spring' => [3, 5],
                    'Summer' => [6, 8],
                    'Fall'   => [9, 11]
                ];

                if (array_key_exists($month, $seasons)) {
                    $month       = random_int($seasons[$month][0], $seasons[$month][1]);
                    $publishDate = Carbon::createFromFormat('Y-m-d H:i', "{$year}-{$month}-01" . ' 00:00');
                } else {
                    $publishDate = Carbon::createFromFormat('M Y d H:i', $date . ' 01 00:00');
                }
            }
        }

        return $publishDate;
    }

    private function importImages()
    {
        $products = [];
        foreach (Product::all() as $product) {
            $products[$product->id] = $product;
        }

        $dir    = storage_path('products');
        $images = scandir($dir);

        foreach ($images as $index => $image) {
            $imagePath = $dir . '/' . $image;

            if (!file_exists($imagePath) || !preg_match('/\(([^)]+)\)/', $image, $matches)) {
                echo 'invalid image => ' . $image . "\r\n";
                continue;
            }

            $id = (int)$matches[1];
            if (!isset($products[$id])) {
                echo 'invalid id => ' . $id . "\r\n";
                continue;
            }

            $extension            = last(explode('.', $image));
            $baseName             = empty($products[$id]->sku) ? $products[$id]->url : $products[$id]->sku;
            $products[$id]->image = $baseName . '.' . $extension;

            $finalPath = storage_path('app/public/products/' . $products[$id]->image);
            copy($imagePath, $finalPath);

            $products[$id]->save();
        }
    }
}
