<template>
  <div class="review-form edit-form">
    <div class="review-form-header d-flex justify-content-between">
      <h2>Leave Review</h2>
      <button @click="onClose()" class="btn">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="row field-row m-b-10">
      <div class="col-xs-12 col-sm-6">
        <label><a href="/profile">change your nickname here</a></label>

        <input
            type="text"
            name="nickname"
            :class="{
              'form-control': true,
              'is-invalid': errors.has('nickname')
            }"
            :value="nickname"
            :disabled="true"
            placeholder="Nickname"
        >
      </div>

      <div class="col-xs-12 col-sm-6">
        <input
            type="text"
            name="title"
            :class="{
              'title-field': true,
              'form-control': true,
              'capital': true,
              'is-invalid': errors.has('title')
            }"
            maxlength="255"
            v-model="title"
            v-validate="'required|max:255'"
            placeholder="Title"
        >

        <span v-show="errors.has('title')" class="invalid-feedback">
          <strong>{{ errors.first('title') }}</strong>
        </span>
      </div>
    </div>

    <div class="row field-row m-b-10">
      <div class="col-lg-12">
        <textarea
            v-model="description"
            :class="{
              'form-control': true,
              'is-invalid': errors.has('review')
            }"
            placeholder="Write Your Review"
            v-validate="'required|max:3000'"
            name="review"
        />

        <span v-show="errors.has('review')" class="invalid-feedback">
            <strong>{{ errors.first('review') }}</strong>
        </span>
      </div>
    </div>

    <div class="review-form-footer">
      <div class="add-rating">
        <h2>How much did you like this book?</h2>
        <star-rating
            v-model="rating"
            class="rating"
            :show-rating="false"
            :star-size="22"
            active-color="#f9b800"
            animate
            name="rating"
        />
      </div>

      <button
          @click="submit()"
          class="le-button button d-flex align-items-center"
      >
        <i class="fas fa-comment m-r-10"></i>Leave Review
      </button>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex';
import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

export default {
  mixins: [
    SnotifyWrapper
  ],

  props: {
    nickname: {
      type    : String,
      required: true
    },

    productId: {
      type    : String,
      required: true
    },

    setLoader: {
      type    : Function,
      required: true
    },

    userReview: {
      type    : Object,
      default : null
    },
  },

  data: () => ({
    title      : '',
    description: '',
    rating     : 5
  }),

  mounted() {
    if (this.userReview) {
      this.title       = this.userReview.title
      this.description = this.userReview.description
      this.rating      = this.userReview.rating
    }
  },

  methods: {
    ...mapActions(['pushReview']),

    async submit() {
      const success = await this.$validator.validate();
      if (!success) {
        return;
      }

      let data = {
        productId  : this.productId,
        title      : this.title,
        description: this.description,
        rating     : this.rating
      };

      this.setLoader(true);

      try {
        await this.pushReview(data);
        this.showSuccessToast('Your review has been submitted! Thank you!');
        this.$emit('added');
        this.onClose();
      } catch (e) {
        this.showErrorToast(e.message);
      }

      this.setLoader(false);
    },

    onClose() {
      this.$emit('close');
    }
  }
};
</script>
