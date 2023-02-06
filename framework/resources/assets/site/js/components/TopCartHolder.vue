<template>
  <div v-if="!floatingCart || (floatingCart && cartItemsCounter)" class="top-cart-holder dropdown animate-dropdown" :class="{'is-loading': cartAction}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="basket">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <div class="basket-item-count">
          <span class="count d-flex align-items-center justify-content-center">{{ cartItemsCounter }}</span>
          <!--                    <img :src="icon" alt=""/>-->
          <div class="top-cart-wrapper d-flex align-items-center">
            <div class="wrapper-cart-icon d-flex align-items-center">
              <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M5.1 14.1C4.165 14.1 3.4085 14.865 3.4085 15.8C3.4085 16.735 4.165 17.5 5.1 17.5C6.035 17.5 6.8 16.735 6.8 15.8C6.8 14.865 6.035 14.1 5.1 14.1ZM0 0.5V2.2H1.7L4.76 8.6515L3.6125 10.734C3.4765 10.972 3.4 11.2525 3.4 11.55C3.4 12.485 4.165 13.25 5.1 13.25H15.3V11.55H5.457C5.338 11.55 5.2445 11.4565 5.2445 11.3375L5.27 11.2355L6.035 9.85H12.3675C13.005 9.85 13.566 9.5015 13.855 8.9745L16.898 3.458C16.966 3.339 17 3.1945 17 3.05C17 2.5825 16.6175 2.2 16.15 2.2H3.5785L2.7795 0.5H0ZM13.6 14.1C12.665 14.1 11.9085 14.865 11.9085 15.8C11.9085 16.735 12.665 17.5 13.6 17.5C14.535 17.5 15.3 16.735 15.3 15.8C15.3 14.865 14.535 14.1 13.6 14.1Z"
                    fill="black"/>
              </svg>
            </div>
            <div class="wrapper-cart-icon-arrow d-flex align-items-center">
              <svg width="6" height="5" viewBox="0 0 6 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M2.60453 3.98528C2.80468 4.24529 3.19678 4.24529 3.39694 3.98528L5.84515 0.804963C6.09825 0.476178 5.86386 -3.43323e-05 5.44894 -3.43323e-05H0.55252C0.137601 -3.43323e-05 -0.0967805 0.476178 0.156318 0.804963L2.60453 3.98528Z"
                    fill="black"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="total-price-basket">
          <span class="lbl">your cart:</span>
          <span class="total-price">
                        <span class="sign">$</span><span class="value">{{ cart.total }}</span>
                    </span>
        </div>
      </a>

      <div class="dropdown-menu dropdown-basket" v-if="cartItemsCounter">
        <div class="basket-item">
          <div class="title-modal">
            Your cart
          </div>
          <div class="description-modal">
            There are <span>{{ cart.items.length }} <span v-if="cart.items.length === 1">item</span><span v-else>items</span></span> in the basket in the amount of
            <span>${{ cart.subTotal }}</span>
          </div>
        </div>
        <div class="checkout">
          <div class="basket-item">
            <!--                        <a :href="checkoutUrl" class="le-button">Proceed to Checkout</a>-->
            <a :href="cartUrl" class="le-button" @click="throwEvent">View Shopping Cart</a>
          </div>
        </div>
      </div>
    </div><!-- /.basket -->
  </div><!-- /.top-cart-holder -->
</template>

<script>
import ShoppingCartMixin from '../mixins/ShoppingCart';

export default {
  mixins: [
    ShoppingCartMixin
  ],

  props: [
    'icon',
    'cart-url',
    'checkout-url',
    'floating-cart'
  ],

  computed: {
    extraItems() {
      return Math.max(this.cartItemsCounter - this.maxItemsListed, 0);
    }
  },

  data() {
    return {
      maxItemsListed: 3
    };
  },

  methods: {
    throwEvent() {
      if (this.floatingCart) {
        ga('send', 'event', 'Click on cart icon', 'Floating');
      } else {
        ga('send', 'event', 'Click on cart icon', 'Header');
      }
    }
  }
};
</script>
