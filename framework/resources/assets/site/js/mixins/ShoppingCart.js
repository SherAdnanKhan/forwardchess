import {mapGetters} from 'vuex';
import CartTotals from '../components/CartTotals';

export default {
    components: {
        CartTotals
    },

    computed: {
        ...mapGetters([
            'cart',
            'cartAction'
        ]),

        cartItemsCounter() {
            return this.cart.items.length;
        }
    }
}