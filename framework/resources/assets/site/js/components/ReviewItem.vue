<template>
  <div class="col-lg-12">
    <div class="review-item">
      <div class="review-item-header">
        <h3 class="nickname">{{ review.nickname }}</h3>

        <star-rating
            class="rating"
            :show-rating="false"
            :star-size="20"
            active-color="#f9b800"
            read-only
            :rating="review.rating"
        />
      </div>

      <h2 class="title">{{ review.title }}</h2>

      <nl2br tag="p" :text="review.description" class-name="description" />

      <small>{{ date }}</small>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import StarRating from 'vue-star-rating';
import Nl2br from 'vue-nl2br'

export default {
  name: 'ReviewItem',

  components: {
    StarRating,
    Nl2br
  },

  props: {
    review: {
      type    : Object,
      required: true
    }
  },

  computed: {
    date() {
      return moment(this.review.created_at).format('MM/DD/YYYY');
    }
  }
};
</script>