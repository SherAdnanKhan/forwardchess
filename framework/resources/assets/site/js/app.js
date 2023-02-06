import ApiClient from '../../common/providers/ApiClient';
import store from './store';
import Snotify, {SnotifyPosition} from 'vue-snotify';
import SearchForm from './components/SearchForm';
import AddToCartButton from './components/AddToCartButton';
import AddToWishlistButton from './components/AddToWishlistButton';
import RemoveFromCartButton from './components/RemoveFromCartButton';
import GiftCardButton from './components/GiftCardButton';
import TopCartHolder from './components/TopCartHolder';
import TopWishlistHolder from './components/TopWishlistHolder';
import ShoppingCart from './components/ShoppingCart';
import PlaceOrder from './components/PlaceOrder';
import BuyGiftCard from './components/BuyGiftCard';
import Subscribe from './components/Subscribe';
import CustomSelect from '../../common/components/CustomSelect';
import ApiError from '../../common/components/ApiError';
import PasswordModal from './components/PasswordModal';
import EditProfileModal from './components/EditProfileModal';
import EditPhoneModal from './components/EditMobileModal';
import PriceFilter from './components/PriceFilter';
import ReviewList from './components/ReviewList';
import StarRating from 'vue-star-rating';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
};


window.Vue.use(Snotify, options);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Vue.component('search-form', SearchForm);
window.Vue.component('button-add-to-cart', AddToCartButton);
window.Vue.component('button-add-to-wishlist', AddToWishlistButton);
window.Vue.component('button-remove-from-cart', RemoveFromCartButton);
window.Vue.component('button-buy-gift-card', GiftCardButton);
window.Vue.component('top-cart-holder', TopCartHolder);
window.Vue.component('top-wishlist-holder', TopWishlistHolder);
window.Vue.component('shopping-cart', ShoppingCart);
window.Vue.component('place-order', PlaceOrder);
window.Vue.component('buy-gift-card', BuyGiftCard);
window.Vue.component('subscribe', Subscribe);
window.Vue.component('custom-select', CustomSelect);
window.Vue.component('api-error', ApiError);
window.Vue.component('password-modal', PasswordModal);
window.Vue.component('edit-profile-modal', EditProfileModal);
window.Vue.component('edit-mobile-modal', EditPhoneModal);
window.Vue.component('price-filter', PriceFilter);
window.Vue.component('review-list', ReviewList);
window.Vue.component('star-rating', StarRating);

$(document).ready(function () {
  store.dispatch('setCart', window.cart);
  store.dispatch('setWishlist', window.wishlist);

  new window.Vue({
    store,
    el     : '#app',
    provide: {
      api: ApiClient
    }
  });
});
