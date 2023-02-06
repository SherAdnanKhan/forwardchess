<?php

namespace App\Console\Commands;

use App\Models\Product\Category;
use App\Models\Product\Product;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories {product?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories from the old site';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $products;

    /**
     * @var string
     */
    private $storageFile = 'categories.json';

    /**
     * Create a new command instance.
     *
     * @param HttpClient $client
     *
     * @return void
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct();

//        $this->client     = $client;
//        $this->categories = [];
//
//        foreach (Category::all() as $category) {
//            $this->categories[$this->category($category->name)] = $category->id;
//        }
//
//        $this->products = $this->getProductsFromStorage();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        if (!empty($productId = $this->argument('product'))) {
//            $product = Product::find($productId);
//            if (!empty($product)) {
//                $this->importProduct($product, 0);
//            } else {
//                $this->error('Product not found!');
//            }
//        } else {
//            $products = [];
//            foreach (Product::all() as $product) {
//                if (isset($this->products[$product->id])) {
//                    continue;
//                }
//
//                $products[] = $product;
//            }
//
//            if (empty($products)) {
//                $this->error('All products already scanned!');
//            } else {
//                $this->line('Found ' . count($products) . ' products to scan.');
//
//                foreach ($products as $counter => $product) {
//                    $product->counter = $counter + 1;
//                    $this->importProduct($product);
//                }
//            }
//        }
    }

    private function importProduct(Product $product, $sleep = 2)
    {
        $consoleName = "`{$product->title}`({$product->id})";

        if (isset($this->products[$product->id])) {
            $this->line("Skip categories for {$consoleName}. Already imported.");
        } else {
            $message = ($product->counter ? "#{$product->counter}. " : ' ') . "Importing categories for {$consoleName}";

            $this->line($message);

            try {
                $response = $this->client->request('GET', 'https://forwardchess.com/product/' . $product->url);
                $html     = $response->getBody()->getContents();

                preg_match_all('/\<span class="posted_in"\>(.*?)\<\/span\>/', $html, $matches);
                if (!empty($matches)) {
                    $this->importCategories($product, $matches[0][0]);
                }
            } catch (\Exception $e) {
                dd($e);
            }

            if ($sleep) {
                sleep($sleep);
            }
        }
    }

    private function importCategories(Product $product, $string): bool
    {
        preg_match_all('/<a[^>]*>(.*?)<\/a>/', $string, $matches);
        if (count($matches) < 2) {
            return false;
        }

        $categories = [];
        foreach ($matches[1] as $match) {
            $name = $this->category($match);
            if (isset($this->categories[$name])) {
                $categories[] = $this->categories[$name];
            }
        }

        if (!empty($categories)) {
            $this->products[$product->id] = $categories;
            $this->saveProductsToStorage();
        }

        return true;
    }

    /**
     * @return array
     */
    private function getProductsFromStorage()
    {
        return Storage::exists($this->storageFile) ? json_decode(Storage::read($this->storageFile), true) : [];
    }

    /**
     * @return $this
     */
    private function saveProductsToStorage()
    {
        Storage::put($this->storageFile, json_encode($this->products));

        return $this;
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function category($name)
    {
        return strtolower($name);
    }
}
