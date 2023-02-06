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
use App\Policies\Blog\ArticlePolicy;
use App\Policies\Blog\TagPolicy;
use App\Policies\CouponPolicy;
use App\Policies\FaqPolicy;
use App\Policies\GiftPolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\Order\BillingPolicy as OrderBillingPolicy;
use App\Policies\Order\OrderPolicy;
use App\Policies\Order\PaymentPolicy as OrderPaymentPolicy;
use App\Policies\Product\CategoryPolicy;
use App\Policies\Product\ProductPolicy;
use App\Policies\Product\PublisherPolicy;
use App\Policies\Product\ReviewPolicy;
use App\Policies\TaxRatePolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\User\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Coupon::class       => CouponPolicy::class,
        Gift::class         => GiftPolicy::class,
        Faq::class          => FaqPolicy::class,
        Testimonial::class  => TestimonialPolicy::class,
        Newsletter::class   => NewsletterPolicy::class,
        TaxRate::class      => TaxRatePolicy::class,

        /**************** user ****************/
        User::class         => UserPolicy::class,

        /**************** product ****************/
        Publisher::class    => PublisherPolicy::class,
        Category::class     => CategoryPolicy::class,
        Product::class      => ProductPolicy::class,
        Review::class       => ReviewPolicy::class,

        /**************** Order ****************/
        Order::class        => OrderPolicy::class,
        OrderBilling::class => OrderBillingPolicy::class,
        OrderPayment::class => OrderPaymentPolicy::class,

        /**************** blog ****************/
        Tag::class          => TagPolicy::class,
        Article::class      => ArticlePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null, ['prefix' => 'api']);
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
