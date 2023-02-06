<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <ReviewsList title="Approve product reviews"/>
  </div>
</template>

<script>
import {REVIEWS_NAME} from '../../../router/names';
import ListingPage from '../../layout/ListingPage.vue';
import ReviewsList from './../../reviews/ReviewsList';

export default {
  extends: ListingPage,

  components: {
    ReviewsList
  },

  data: () => ({
    pageName: REVIEWS_NAME
  })
};
</script>