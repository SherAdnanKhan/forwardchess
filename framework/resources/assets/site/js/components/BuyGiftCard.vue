<template>
  <section id="checkout-page" :class="{'is-loading': isLoading}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="container">
      <div class="errors-container">
        <div class="col-xs-12 no-margin">
          <api-error :error="error"></api-error>
        </div>
      </div>

      <p>After the payment is completed, your friend will receive the gift card on the entered email address.</p>

      <form method="post" class="edit-form" @submit.prevent="placeOrder">
        <div class="col-xs-12 no-margin">
          <div class="billing-address">
            <h2 class="border h1">Customer details</h2>
            <div class="row field-row">
              <div class="col-xs-12 col-sm-6">
                <label>Email</label>
                <input class="le-input" :readonly="true" :value="email">
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
            <h2 class="border h1">Gift details</h2>

            <div class="row order-item">
              <div class="col-xs-12">
                <div class="title">Amount</div>
                ${{ gift.amount }}
              </div>
            </div>

            <div class="row order-item">
              <div class="col-xs-12">
                <div class="title">Friend name</div>
                {{ gift.name }}
              </div>
            </div>

            <div class="row order-item">
              <div class="col-xs-12">
                <div class="title">Friend email</div>
                {{ gift.email }}
              </div>
            </div>

            <div class="row order-item">
              <div class="col-xs-12">
                <div class="title">Your message</div>
                {{ gift.message }}
              </div>
            </div>
          </section>

          <div id="total-area" class="row no-margin">
            <div class="col-xs-12 col-lg-4 col-lg-offset-8 no-margin-right">
              <div id="subtotal-holder">
                <ul id="total-price" class="tabled-data inverse-bold no-border">
                  <li>
                    <label>order total</label>
                    <div class="value pull-right">${{ gift.amount }}</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="place-order-button">
            <button class="le-button big" type="submit" :disabled="isLoading">place order</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import PlaceOrderMixin from '../mixins/PlaceOrder';
import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

export default {
  inject: [
    'api'
  ],

  mixins: [
    PlaceOrderMixin,
    SnotifyWrapper,
  ],

  props: {
    giftDetails: {
      type    : String,
      required: true
    }
  },

  data() {
    return {
      gift: JSON.parse(this.giftDetails)
    };
  }
};
</script>
