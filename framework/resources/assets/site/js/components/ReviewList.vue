<template>
  <div class="row reviews-list" :class="{'is-loading': isLoading, 'with-pagination': hasMore}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="col-lg-12">
      <div class="d-flex justify-content-between">
        <h1 class="m-l-25">
          <template v-if="total">
            {{ total }}
            {{ (total == 1) ? 'Review' : 'Reviews' }}
          </template>
          <template v-else>No Reviews</template>

          <star-rating
              v-if="ratingValue"
              class="rating"
              :show-rating="true"
              :star-size="20"
              active-color="#f9b800"
              read-only
              :rating="ratingValue"
              :round-start-rating="false"
          />
        </h1>

        <div v-if="addAllowed">
          <button
              v-if="!addState"
              class="m-r-25 btn-sm le-button button d-flex align-items-center"
              @click="setAddState(true)"
          >
            <i class="fas fa-comment m-r-10"></i>Leave Review
          </button>
        </div>
      </div>

      <ReviewForm
          v-if="addState"
          :nickname="nickname"
          :product-id="this.productId"
          :set-loader="setLoader"
          :user-review="userReview"
          @added="added = true"
          @close="setAddState"
      />

      <div v-if="total" class="row">
        <ReviewItem
            v-for="review in reviews"
            :key="review.id"
            :review="review"
        />

        <button
            v-if="hasMore"
            class="block more-reviews-btn"
            @click="fetch()"
        >
          View More
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex';
import ReviewForm from './ReviewForm';
import SnotifyWrapper from '../mixins/SnotifyWrapper';
import ReviewItem from './ReviewItem';

export default {
  components: {
    ReviewForm,
    ReviewItem
  },

  mixins: [
    SnotifyWrapper
  ],

  props: [
    'canAdd',
    'nickname',
    'productId'
  ],

  data: () => ({
    added      : false,
    isLoading  : false,
    addState   : false,
    reviews    : [],
    total      : 0,
    currentPage: 0,
    lastPage   : 0,
    rating     : null,
    userReview : {}
  }),

  computed: {
    hasMore() {
      return this.currentPage < this.lastPage;
    },

    ratingValue() {
      return this.rating ? Number(this.rating) : null;
    },

    addAllowed() {
      return this.canAdd;
    }
  },

  created() {
    this.reset();
    this.fetch();
  },

  methods: {
    ...mapActions(['fetchReviews']),

    reset() {
      this.currentPage = 0;
      this.total = 0;
      this.total = 0;
      this.reviews = [];
    },

    async fetch() {
      this.setLoader(true);

      try {
        const page = this.currentPage + 1;

        const {reviews, total, lastPage, rating, userReview} = await this.fetchReviews({productId: this.productId, page});
        this.reviews = [].concat(this.reviews).concat(reviews);
        this.total = total;
        this.lastPage = lastPage;
        this.rating = rating;
        this.currentPage = page;
        this.userReview = userReview;
      } catch (e) {
        this.showErrorToast(e.message);
      }

      this.setLoader(false);
    },

    setAddState(state = false) {
      this.reset();
      this.fetch();
      this.addState = state;
    },

    setLoader(state) {
      this.isLoading = state;
    }
  }
};
</script>
