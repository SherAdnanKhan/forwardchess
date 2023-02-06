<?php

namespace App\Providers;

use App\Common\Cache\CacheStorage;
use App\Common\IpLocator;
use App\Common\MobileGateway;
use App\Common\OrdersProcessor;
use App\Common\SyncOrdersProcessor;
use App\Common\PaypalPayment;
use App\Common\ReferralGateway;
use App\Common\SubscribersGateway;
use App\Contracts\BlockedProductsInterface;
use App\Contracts\CacheStorageInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\CountriesServiceInterface;
use App\Contracts\IpLocatorInterface;
use App\Contracts\MailChimpServiceInterface;
use App\Contracts\MobileGatewayInterface;
use App\Contracts\OrdersProcessorInterface;
use App\Contracts\SyncOrdersProcessorInterface;
use App\Contracts\PaypalPaymentInterface;
use App\Contracts\ReferralGatewayInterface;
use App\Contracts\SubscribersGatewayInterface;
use App\Contracts\WishlistServiceInterface;
use App\MailChimp\MailChimpService;
use App\Models\AbstractModel;
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
use App\Models\Wishlist;
use App\Repositories\Blog\ArticleRepository;
use App\Repositories\Blog\TagRepository;
use App\Repositories\CouponRepository;
use App\Repositories\FaqRepository;
use App\Repositories\GiftRepository;
use App\Repositories\NewsletterRepository;
use App\Repositories\Order\BillingRepository as OrderBillingRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\PaymentRepository as OrderPaymentRepository;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\PublisherRepository;
use App\Repositories\Product\ReviewRepository;
use App\Repositories\TaxRateRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Wishlist\WishlistDatabaseRepository;
use App\Services\BlockedProductsService;
use App\Services\CartService;
use App\Services\CountriesService;
use App\Services\WishlistService;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoriesProvider
 * @package App\Providers
 */
class RepositoriesProvider extends ServiceProvider
{
    private array $repositoriesList = [
        CouponRepository::class           => Coupon::class,
        GiftRepository::class             => Gift::class,
        TaxRateRepository::class          => TaxRate::class,
        FaqRepository::class              => Faq::class,
        TestimonialRepository::class      => Testimonial::class,
        NewsletterRepository::class       => Newsletter::class,
        WishlistDatabaseRepository::class => Wishlist::class,

        // user
        UserRepository::class             => User::class,

        // product
        PublisherRepository::class        => Publisher::class,
        CategoryRepository::class         => Category::class,
        ProductRepository::class          => Product::class,
        ReviewRepository::class           => Review::class,

        // page
        ArticleRepository::class          => Article::class,
        TagRepository::class              => Tag::class,

        // order
        OrderRepository::class            => Order::class,
        OrderBillingRepository::class     => OrderBilling::class,
        OrderPaymentRepository::class     => OrderPayment::class,
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CacheStorageInterface::class,
            CacheStorage::class
        );

        $this->app->bind(
            CartServiceInterface::class,
            CartService::class
        );

        $this->app->bind(
            WishlistServiceInterface::class,
            WishlistService::class
        );

        $this->app->bind(
            CountriesServiceInterface::class,
            CountriesService::class
        );

        $this->app->bind(
            MobileGatewayInterface::class,
            MobileGateway::class
        );

        $this->app->bind(
            PaypalPaymentInterface::class,
            PaypalPayment::class
        );

        $this->app->bind(
            SubscribersGatewayInterface::class,
            SubscribersGateway::class
        );

        $this->app->bind(
            OrdersProcessorInterface::class,
            OrdersProcessor::class
        );

        $this->app->bind(
            SyncOrdersProcessorInterface::class,
            SyncOrdersProcessor::class
        );

        $this->app->bind(
            BlockedProductsInterface::class,
            BlockedProductsService::class
        );

        $this->app->bind(
            ReferralGatewayInterface::class,
            ReferralGateway::class
        );

        $this->app->bind(
            IpLocatorInterface::class,
            IpLocator::class
        );

        $this->app->bind(
            MailChimpServiceInterface::class,
            MailChimpService::class
        );

        foreach ($this->repositoriesList as $repository => $resource) {
            $this->app->when($repository)
                ->needs(AbstractModel::class)
                ->give(function () use ($resource) {
                    return new $resource;
                });
        }
    }
}
