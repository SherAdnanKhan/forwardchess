<template>
  <div class="edit-page">
    <div v-show="isLoading" class="row loader-container">
      <spinner></spinner>
      <h3 class="text-center">{{ loaderText }}</h3>
    </div>

    <div v-show="!isLoading">
      <div v-show="isEmpty" class="empty-state-container row">
        <h3 class="text-center text-danger">{{ emptyText }}</h3>
      </div>

      <slot name="main-content"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfilePage',

  props: {
    isLoading: {
      type   : Boolean,
      default: false
    },

    isEmpty: {
      type   : Boolean,
      default: false
    },

    loaderText: {
      type   : String,
      default: 'Loading data...'
    },

    emptyText: {
      type   : String,
      default: 'No data to display.'
    }
  }
};
</script>
