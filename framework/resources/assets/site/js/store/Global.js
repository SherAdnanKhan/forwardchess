import _ from 'lodash';
import HttpClient, {getResponse} from '../../../common/utils/HttpClient';

const state = {
  cart            : null,
  cartAction      : false,
  cartItemsIds    : [],
  wishlist        : null,
  wishlistAction  : false,
  wishlistItemsIds: []
};

const getters = {
  cart            : state => state.cart,
  cartAction      : state => state.cartAction,
  cartItemsIds    : state => state.cartItemsIds,
  wishlist        : state => state.wishlist,
  wishlistAction  : state => state.wishlistAction,
  wishlistItemsIds: state => state.wishlistItemsIds
};

const client = HttpClient(null, false);
const dataRequest = httpRequest => httpRequest.then(getResponse);

const cartAction = ({dispatch}, promise) => {
  if (state.cartAction) {
    return Promise.reject('Another action is already running! Please try again!');
  }

  dispatch('setCartAction', true);

  return promise
    .then(cart => {
      dispatch('setCart', cart);
      dispatch('setCartAction', false);

      return cart;
    })
    .catch(error => {
      dispatch('setCartAction', false);
      return Promise.reject(error);
    });
};

const wishlistAction = ({dispatch}, promise) => {
  if (state.wishlistAction) {
    return Promise.reject('Another action is already running! Please try again!');
  }
  dispatch('setWishlistAction', true);

  return promise
    .then(wishlist => {
      dispatch('setWishlist', wishlist);
      dispatch('setWishlistAction', false);

      return wishlist;
    })
    .catch(error => {
      dispatch('setWishlistAction', false);
      return Promise.reject(error);
    });
};

const findItemById = (items, productId) => _.find(items, item => String(item.id) === String(productId));

const actions = {
  setCartAction({commit}, cartAction) {
    commit('setCartAction', cartAction);
  },

  setCart({commit}, cart) {
    commit('setCart', cart);
  },

  addItemToCart(store, payload) {
    return cartAction(store, dataRequest(client.post(`/cart/items`, payload)))
      .then(cart => findItemById(cart.items, payload.productId));
  },

  removeItemFromCart(store, productId) {
    const item = findItemById(store.state.cart.items, productId);

    return cartAction(store, dataRequest(client.delete(`/cart/items/${productId}`))).then(() => item);
  },

  addCouponToCart(store, coupon) {
    return cartAction(store, dataRequest(client.post(`/cart/coupon/${coupon}`)));
  },

  addGiftToCart(store, gift) {
    return cartAction(store, dataRequest(client.post(`/cart/gift/${gift}`)));
  },

  setWishlistAction({commit}, wishlistAction) {
    commit('setWishlistAction', wishlistAction);
  },

  setWishlist({commit}, wishlist) {
    commit('setWishlist', wishlist);
  },

  addItemToWishlist(store, productId) {
    return wishlistAction(store, dataRequest(client.post(`/wishlist/${productId}`)));
  },

  removeItemFromWishlist(store, productId) {
    return wishlistAction(store, dataRequest(client.delete(`/wishlist/${productId}`)));
  },

  async fetchReviews({}, {productId, page = 1}) {
    return dataRequest(client.get(`/products/${productId}/reviews?page=${page}`));
  },

  pushReview({}, data) {
    return client.post(`/reviews`, data);
  }
};

const mutations = {
  setCartAction(state, cartAction) {
    state.cartAction = cartAction;
  },

  setCart(state, cart) {
    const cartItemsIds = [];
    _.each(cart.items, item => {
      cartItemsIds.push(item.id);
    });

    state.cart = cart;
    state.cartItemsIds = cartItemsIds;
  },

  setWishlistAction(state, wishlistAction) {
    state.wishlistAction = wishlistAction;
  },

  setWishlist(state, wishlist) {
    const wishlistItemsIds = [];
    _.each(wishlist, item => {
      wishlistItemsIds.push(item);
    });

    state.wishlist = wishlist;
    state.wishlistItemsIds = wishlistItemsIds;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
