<template>
  <section id="cart-page" :class="{'is-loading': cartAction}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="container">
      <template v-if="cartItemsCounter">
        <div class="row">
          <div class="col-lg-12">
            <div class="title m-b-40">
              Your shopping cart
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-no-padding">
            <div class="items-holder">
              <div v-for="item in cart.items" :key="item.id" class="cart-item d-flex">
                <div class="image-cart">
                  <a :href="item.product.url">
                    <img :alt="item.product.title" :src="item.product.image"/>
                  </a>
                </div>
                <div class="wrapper-content-cart">
                  <div class="title-cart">
                    <div class="title-product">
                      <a :href="item.product.url">
                        {{ item.product.title }}
                      </a>
                    </div>
                    <template v-if="item.product.isBundle == '0'">
                      <div class="brand"><span>Publisher:</span> {{ item.product.publisher }}</div>
                      <div class="brand"><span>Author:</span> {{ item.product.author }}</div>
                    </template>
                    <template v-else>
                      <ul>
                        <li v-for="children in item.product.children" :key="children.id">
                          <span :class="{ 'price-prev': children.alreadyBought }">{{ children.title }} - $ {{ children.sellPrice }} </span>{{ children.alreadyBought ? '(owned)' : '' }}
                        </li>
                      </ul>
                    </template>
                  </div>
                  <div class="price">
                    ${{ item.total }}
                  </div>
                </div>
                <button-remove-from-cart :product="item.id"></button-remove-from-cart>
              </div><!-- /.cart-item -->
            </div>

            <div v-if="cart.coupon" class="cart-item d-flex discount-item">
              <div class="wrapper-content-cart row">
                <div class="title-cart">
                  <div class="title-product">
                    <span>Promotion discount: {{ cart.coupon.name }}</span>
                  </div>
                </div>
                <div class="price">
                  -${{ cart.coupon.discount }}
                </div>
              </div>
            </div><!-- /.cart-item -->

            <div v-for="gift in cart.gifts" :key="gift.code" class="cart-item d-flex discount-item">
              <div class="wrapper-content-cart row">
                <div class="title-cart">
                  <div class="title-product">
                    <span> Gift card discount: {{ gift.code }}</span>
                  </div>
                  <div style="font-size: 11px; font-style: italic;">Any unused amount of the gift card will be available for future purchases</div>
                </div>
                <div class="price">
                  -${{ gift.amount }}
                </div>
              </div>
            </div><!-- /.cart-item -->
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-no-padding">
            <div class="sidebar">
              <div class="cart-summary">
                <div class="title-cart">Your Order</div>
                <div class="body">
                  <CartTotals :cart="cart"></CartTotals>
                </div>
              </div><!-- /.widget -->

              <div id="cupon-widget" class="">
                <div class="body">
                  <form @submit.prevent="addCoupon" class="code-form">
                    <div class="inline-input">
                      <input type="text"
                             class="le-input"
                             placeholder="Enter coupon code"
                             name="coupon"
                             v-model="coupon"
                             v-validate="'required'"
                      />
                      <button class="" type="submit">Apply</button>
                    </div>
                    <span v-show="errors.has('coupon')" class="error-msg">Enter a valid coupon!</span>
                  </form>
                </div>
              </div><!-- /.widget -->

              <div class="clearfix"></div>

              <div id="gift-widget" class="">
                <div class="body">
                  <form @submit.prevent="addGiftCard">
                    <div class="inline-input">
                      <input type="text"
                             class="le-input"
                             placeholder="Enter gift card code"
                             name="gift"
                             v-model="gift"
                             v-validate="'required'"
                      />
                      <button class="" type="submit">Apply</button>
                    </div>
                    <span v-show="errors.has('gift')" class="error-msg">Enter a valid gift card code!</span>
                  </form>
                </div>
              </div><!-- /.widget -->
              <div class="buttons-holder">
                <a class="le-button d-flex align-items-center justify-content-center" @click.prevent="goToCheckout">Proceed to Checkout</a>
                <a class="simple-link d-flex align-items-center justify-content-center" :href="productsUrl">Continue Shopping</a>
              </div>
            </div>
          </div><!-- /.sidebar -->
        </div><!-- /.row -->
      </template>
      <div v-else>
        <div class="container">
          <div class="row empty-cart">
            <div class="col-lg-12">
              <div class="cart-image d-flex justify-content-center">
                <img src="/images/Cart1.svg" alt="">
              </div>
              <div class="primary-title d-flex justify-content-center m-b-25">
                Your cart is empty
              </div>
              <div class="wrapper-link d-flex justify-content-center">
                <a :href="productsUrl" class="le-button shopping-cart-link">Add to Cart Your First E-Book</a>
              </div>
            </div>
          </div>
        </div>
        <!--                <h2 class="text-center" style="text-transform: none;">You don't have products added in the shopping cart!</h2>-->
      </div>
    </div>
  </section>
</template>

<script>
import {mapActions} from 'vuex';
import ShoppingCartMixin from '../mixins/ShoppingCart';
import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

export default {
  mixins: [
    ShoppingCartMixin,
    SnotifyWrapper
  ],

  props: [
    'products-url',
    'checkout-url'
  ],

  data: () => ({
    coupon: '',
    gift  : ''
  }),

  methods: {
    ...mapActions([
      'addCouponToCart',
      'addGiftToCart'
    ]),

    async goToCheckout() {
      let allGood = true;
      if (this.coupon) {
        allGood = await this.addCoupon();
      }

      if (this.gift) {
        allGood = await this.addGiftCard();
      }

      if (allGood) {
        window.top.location = this.checkoutUrl;
      }
    },

    async addCoupon() {
      const isValid = await this.$validator.validate('coupon', this.coupon);
      if (!isValid) {
        return false;
      }

      try {
        await this.addCouponToCart(this.coupon);
        this.showSuccessToast('The coupon was added!');
        this.coupon = '';
        return true;
      } catch (e) {
        this.showErrorToast(e.message);
        return false;
      }
    },

    async addGiftCard() {
      const isValid = await this.$validator.validate('gift', this.gift);
      if (!isValid) {
        return false;
      }

      try {
        await this.addGiftToCart(this.gift);
        this.showSuccessToast('The coupon was added!');
        this.gift = '';
        return true;
      } catch (e) {
        this.showErrorToast(e.message);
        return false;
      }
    }
  }
};
</script>
