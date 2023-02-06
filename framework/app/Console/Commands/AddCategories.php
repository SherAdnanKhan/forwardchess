<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AddCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:categories {product?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save categories to the products';

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var string
     */
    private $storageFile = 'categories.json';

    /**
     * @var bool
     */
    private $clearCache = false;

    /**
     * Create a new command instance.
     *
     * @param ProductRepository $productRepository
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();

//        $this->productRepository = $productRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $products = $this->getProductsFromStorage();
//
//        if (!empty($productId = $this->argument('product'))) {
//            if (isset($products[$productId])) {
//                $this->saveCategories($productId, $products[$productId]);
//            } else {
//                $this->error('Nothing to save. Product not scanned or without categories.');
//            }
//        } else {
//            $this->line('Found ' . count($products) . ' products to add categories.');
//
//            $counter = 1;
//            foreach ($products as $productId => $categories) {
//                $this->saveCategories($productId, $categories, $counter);
//                $counter++;
//            }
//        }
//
//        if ($this->clearCache) {
//            Artisan::call('cache:clear');
//        }
    }

    private function saveCategories(int $productId, array $categories, $counter = null)
    {
        /** @var Product $product */
        $product = $this->productRepository->getById($productId);
        if (empty($product)) {
            $this->error("Product with ID {$productId} not found. Skip saving categories.");
        } else {
            $this->line(($counter ? "#{$counter}. " : ' ') . "Adding categories for `{$product->title}`({$product->id})");

            $this->productRepository->saveCategories($product, $categories);
            $this->clearCache = true;
        }
    }

    /**
     * @return array
     */
    private function getProductsFromStorage()
    {
        return Storage::exists($this->storageFile) ? json_decode(Storage::read($this->storageFile), true) : [];
    }
}
