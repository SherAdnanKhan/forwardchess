import * as paths from '../router/paths';
import * as names from '../router/names';

export default [
    {
        title: names.DASHBOARD_NAME,
        url  : paths.DASHBOARD_PATH,
        icon : 'fa fa-dashboard'
    },
    {
        title: names.USERS_NAME,
        url  : paths.USERS_PATH,
        icon : 'fa fa-user'
    },
    {
        title  : 'Shop settings',
        url    : '#',
        icon   : 'fa fa-cogs',
        subMenu: [
            {
                title: names.PUBLISHERS_NAME,
                url  : paths.PUBLISHERS_PATH,
                icon : 'fa fa-circle-o'
            },
            {
                title: names.CATEGORIES_NAME,
                url  : paths.CATEGORIES_PATH,
                icon : 'fa fa-circle-o'
            },
            {
                title: names.PRODUCTS_NAME,
                url  : paths.PRODUCTS_PATH,
                icon : 'fa fa-book'
            },
            {
                title: names.COUPONS_NAME,
                url  : paths.COUPONS_PATH,
                icon : 'fa fa-percent'
            },
            {
                title: names.GIFTS_NAME,
                url  : paths.GIFTS_PATH,
                icon : 'fa fa-gift'
            }
        ]
    },
    {
        title: names.ORDERS_NAME,
        url  : paths.ORDERS_PATH,
        icon : 'fa fa-shopping-cart'
    },
    {
        title: names.FAQS_NAME,
        url  : paths.FAQS_PATH,
        icon : 'fa fa-question'
    },
    {
        title: names.TESTIMONIALS_NAME,
        url  : paths.TESTIMONIALS_PATH,
        icon : 'fa fa-comments'
    },
    {
        title: names.WISHLISTS_NAME,
        url  : paths.WISHLISTS_PATH,
        icon : 'fa fa-heart'
    },
    {
        title: names.REVIEWS_NAME,
        url  : paths.REVIEWS_PATH,
        icon : 'fa fa-star'
    },
    {
        title: names.TAX_RATES_NAME,
        url  : paths.TAX_RATES_PATH,
        icon : 'fa fa-percent'
    },
    {
        title   : 'Blog',
        url     : '#',
        icon    : 'fa fa-newspaper-o',
        subMenu : [
            {
                title : names.BLOG_TAGS_NAME,
                url   : paths.BLOG_TAGS_PATH,
                icon  : 'fa fa-tags'
            },
            {
                title : names.BLOG_ARTICLES_NAME,
                url   : paths.BLOG_ARTICLES_PATH,
                icon  : 'fa fa-file-text'
            },
        ]
    },
];