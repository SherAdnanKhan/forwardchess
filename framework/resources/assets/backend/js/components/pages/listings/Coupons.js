import ListingPage from '../../layout/ListingPage.vue';
import CouponsFilters from '../../filters/CouponsFilters';
import {COUPON_NAME, COUPONS_NAME} from '../../../router/names';

export default {
    extends: ListingPage,

    components: {
        CouponsFilters
    },

    data() {
        return {
            pageName: COUPONS_NAME,
            resourceUrl: 'coupons',
            // filtersName: 'CouponsFilters',
            tableColumns: ['name', 'code', 'type', 'discount', 'startDate', 'endDate', 'actions'],
        };
    },

    methods: {
        onAdd() {
            this.$router.push({
                name: COUPON_NAME,
                params: {
                    coupon: 'add'
                }
            });
        },

        onEdit(couponId) {
            this.$router.push({
                name: COUPON_NAME,
                params: {
                    coupon: couponId
                }
            });
        }
    }
};
