import {mapGetters} from 'vuex';

export default {
    computed: {
        ...mapGetters([
            'wishlist',
            'wishlistAction'
        ]),

        wishlistItemsCounter() {
            return this.wishlist !== null ? Object.keys(this.wishlist).length : 0;
        }
    }
};