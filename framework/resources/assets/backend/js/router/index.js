import Vue from 'vue';
import Router from 'vue-router';
import {
  Categories,
  Coupon,
  Coupons,
  Dashboard,
  Faq,
  Faqs,
  Gift,
  Gifts,
  Order,
  Orders,
  Page,
  Pages,
  Product,
  Products,
  Publishers,
  Reviews,
  Tags,
  TaxRates,
  Testimonial,
  Testimonials,
  User,
  Users,
  Wishlists
} from './routes/index';

Vue.use(Router);

const router = new Router({
  mode  : 'history',
  base  : '/admin/',
  routes: [
    Dashboard,
    Users,
    User,
    Publishers,
    Categories,
    Products,
    Product,
    Coupons,
    Coupon,
    Gifts,
    Gift,
    Order,
    Orders,
    Faq,
    Faqs,
    Testimonials,
    Testimonial,
    Wishlists,
    Pages,
    Page,
    Tags,
    Reviews,
    TaxRates
    //        NotFound
  ]
});

export default router;
