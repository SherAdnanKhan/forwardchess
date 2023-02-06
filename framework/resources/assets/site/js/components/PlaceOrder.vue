<template>
  <section id="checkout-page" :class="{'is-loading': showLoader}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="container">
      <div class="errors-container">
        <div class="col-xs-12 no-margin">
          <api-error :error="error"></api-error>
        </div>
      </div>

      <form method="post" class="edit-form" @submit.prevent="placeOrder">
        <div class="col-xs-12 no-margin">
          <div class="billing-address">
            <h2 class="border h1">Customer details</h2>
            <div class="row field-row">
              <div class="col-xs-12 col-sm-6">
                <label>Email</label>
                <input class="le-input" :readonly="true" :value="email">
              </div>

              <div class="col-xs-12 col-sm-6">
                <label>Country</label>
                <input class="le-input" :readonly="true" :value="country">
              </div>
            </div>

            <div class="row field-row">
              <div class="col-xs-12 col-sm-6">
                <label>First Name*</label>
                <input name="firstName"
                       :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('firstName')}"
                       maxlength="255"
                       v-model="form.firstName"
                       v-validate="'required|max:255'"
                       data-vv-as="first name">

                <span v-show="errors.has('firstName')" class="invalid-feedback">
                    <strong>{{ errors.first('firstName') }}</strong>
                </span>
              </div>

              <div class="col-xs-12 col-sm-6">
                <label>Last Name*</label>
                <input name="lastName"
                       :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('lastName')}"
                       maxlength="255"
                       v-model="form.lastName"
                       v-validate="'required|max:255'"
                       data-vv-as="last name">

                <span v-show="errors.has('lastName')" class="invalid-feedback">
                    <strong>{{ errors.first('lastName') }}</strong>
                </span>
              </div>
            </div>
          </div>

          <section id="your-order">
            <h2 class="border h1">Your order</h2>

            <div v-for="item in cart.items" :key="item.id" class="row order-item">
              <div class="col-xs-12 col-sm-10">
                <div class="title">
                  <a :href="item.product.url">
                    {{ item.product.title }}
                  </a>
                </div>
                <div v-if="!item.product.isBundle" class="brand">Publisher: {{ item.product.publisher }}</div>
                <div v-if="!item.product.isBundle" class="brand">Author: {{ item.product.author }}</div>
                <ul v-if="item.product.isBundle && item.product.children.length > 0">
                  <li v-for="children in item.product.children" :key="children.id">
                    <span :class="{ 'price-prev': children.alreadyBought }">{{ children.title }} - $ {{ children.sellPrice }} </span>{{ children.alreadyBought ? '(owned)' : '' }}
                  </li>
                </ul>
              </div>

              <div class="col-xs-12 col-sm-2">
                <div class="price">${{ item.total }}</div>
              </div>
            </div>

            <div v-if="cart.coupon" class="row order-item">
              <div class="col-xs-12 col-sm-10">
                <div class="title">
                  Promotion discount: {{ cart.coupon.name }}
                </div>
              </div>

              <div class="col-xs-12 col-sm-2">
                <div class="price">
                  -${{ cart.coupon.discount }}
                </div>
              </div>
            </div><!-- /.cart-item -->

            <div v-for="gift in cart.gifts" :key="gift.code" class="row order-item">
              <div class="col-xs-12 col-sm-10">
                <div class="title">
                  Gift card discount: {{ gift.code }}
                </div>
              </div>

              <div class="col-xs-12 col-sm-2">
                <div class="price">
                  -${{ gift.amount }}
                </div>
              </div>
            </div><!-- /.cart-item -->
          </section>

          <div id="total-area" class="row no-margin">
            <div class="col-xs-12 col-lg-4 col-lg-offset-8 no-margin-right">
              <div id="subtotal-holder">
                <CartTotals :cart="cart"></CartTotals>
              </div>
            </div>
          </div>

          <div class="place-order-button">
            <button class="le-button big" type="submit" :disabled="showLoader">place order</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import ShoppingCartMixin from '../mixins/ShoppingCart';
import PlaceOrderMixin from '../mixins/PlaceOrder';
import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

export default {
  inject: [
    'api'
  ],

  mixins: [
    ShoppingCartMixin,
    PlaceOrderMixin,
    SnotifyWrapper,
  ],

  computed: {
    showLoader() {
      return this.cartAction || this.isLoading;
    }
  }
};
</script>
