<?php

namespace App\Providers;

use App\Models\Blog\Article;
use App\Models\Blog\Tag;
use App\Models\Coupon;
use App\Models\Faq\Faq;
use App\Models\Gift;
use App\Models\Newsletter;
use App\Models\Order\Billing as OrderBilling;
use App\Models\Order\Order;
use App\Models\Order\Payment as OrderPayment;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\Publisher;
use App\Models\Product\Review;
use App\Models\TaxRate;
use App\Models\Testimonial;
use App\Models\User\User;
use App\Observers\MainObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

/**
 * Class ObserversProvider
 * @package App\Providers
 */
class ObserversProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private array $models = [
        Coupon::class,
        Gift::class,
        Faq::class,
        Testimonial::class,
        Newsletter::class,
        Publisher::class,
        Category::class,
        Order::class,
        OrderBilling::class,
        OrderPayment::class,
        Review::class,
        Article::class,
        Tag::class,
        TaxRate::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Product::observe(ProductObserver::class);

        foreach ($this->models as $model) {
            forward_static_call_array([$model, 'observe'], [MainObserver::class]);
        }
    }
}
