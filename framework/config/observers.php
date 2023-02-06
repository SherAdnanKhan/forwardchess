<?php

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

return [
    Coupon::class       => CouponRepository::class,
    Gift::class         => GiftRepository::class,
    Faq::class          => FaqRepository::class,
    Testimonial::class  => TestimonialRepository::class,
    Newsletter::class   => NewsletterRepository::class,
    User::class         => UserRepository::class,
    Publisher::class    => PublisherRepository::class,
    Category::class     => CategoryRepository::class,
    Product::class      => ProductRepository::class,
    Order::class        => OrderRepository::class,
    OrderBilling::class => OrderBillingRepository::class,
    OrderPayment::class => OrderPaymentRepository::class,
    Review::class       => ReviewRepository::class,
    Article::class      => ArticleRepository::class,
    Tag::class          => TagRepository::class,
    TaxRate::class      => TaxRateRepository::class,
];
