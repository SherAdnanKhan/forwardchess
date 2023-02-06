<template>
  <div class="top-wishlist-holder dropdown animate-dropdown" :class="{'is-loading': wishlistAction}">
    <div class="mask"></div>
    <div class="spinner"></div>

    <div class="basket">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <div class="basket-item-count">
          <span
            class="count d-flex align-items-center justify-content-center"
          >{{ wishlistItemsCounter }}</span>
          <!--                    <img :src="icon" alt=""/>-->
          <div class="top-wishlist-wrapper d-flex align-items-center">
            <div class="wishlist-icon-wrapper d-flex align-items-center">
              <svg viewBox="0 -28 512.00002 512" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="m471.382812 44.578125c-26.503906-28.746094-62.871093-44.578125-102.410156-44.578125-29.554687 0-56.621094 9.34375-80.449218 27.769531-12.023438 9.300781-22.917969 20.679688-32.523438 33.960938-9.601562-13.277344-20.5-24.660157-32.527344-33.960938-23.824218-18.425781-50.890625-27.769531-80.445312-27.769531-39.539063 0-75.910156 15.832031-102.414063 44.578125-26.1875 28.410156-40.613281 67.222656-40.613281 109.292969 0 43.300781 16.136719 82.9375 50.78125 124.742187 30.992188 37.394531 75.535156 75.355469 127.117188 119.3125 17.613281 15.011719 37.578124 32.027344 58.308593 50.152344 5.476563 4.796875 12.503907 7.4375 19.792969 7.4375 7.285156 0 14.316406-2.640625 19.785156-7.429687 20.730469-18.128907 40.707032-35.152344 58.328125-50.171876 51.574219-43.949218 96.117188-81.90625 127.109375-119.304687 34.644532-41.800781 50.777344-81.4375 50.777344-124.742187 0-42.066407-14.425781-80.878907-40.617188-109.289063zm0 0"
                />
              </svg>
            </div>
            <div class="wrapper-wishlist-icon-arrow d-flex align-items-center">
              <svg
                width="6"
                height="5"
                viewBox="0 0 6 5"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M2.60453 3.98528C2.80468 4.24529 3.19678 4.24529 3.39694 3.98528L5.84515 0.804963C6.09825 0.476178 5.86386 -3.43323e-05 5.44894 -3.43323e-05H0.55252C0.137601 -3.43323e-05 -0.0967805 0.476178 0.156318 0.804963L2.60453 3.98528Z"
                  fill="black"
                />
              </svg>
            </div>
          </div>
        </div>

        <div class="total-price-basket">
          <span class="lbl">your wishlist:</span>
        </div>
      </a>

      <div class="dropdown-menu dropdown-basket" v-if="wishlistItemsCounter">
        <div class="basket-item">
          <div class="title-modal">Your wishlist</div>
          <div class="description-modal">
            <span>
              <span v-if="Object.keys(wishlist).length === 1">There is {{Object.keys(wishlist).length}} item</span>
              <span v-else>There are {{Object.keys(wishlist).length}} items</span>
            </span> in the wishlist
          </div>
        </div>
        <div class="checkout">
          <div class="basket-item">
            <a :href="wishlistUrl" class="le-button">View Wishlist</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import WishlistMixin from "../mixins/Wishlist";

export default {
  mixins: [WishlistMixin],

  props: ["wishlist-url"],

  data() {
    return {
      maxItemsListed: 3,
    };
  },
};
</script>
