import {mapGetters} from 'vuex';
import ListingPage from '../../layout/ListingPage.vue';
import ProductsFilters from '../../filters/ProductsFilters.vue';
import {PRODUCT_NAME, PRODUCTS_NAME} from '../../../router/names';

export default {
    extends: ListingPage,

    components: {
        ProductsFilters
    },

    computed: {
        ...mapGetters([
            'publishers'
        ])
    },

    data() {
        return {
            pageName    : PRODUCTS_NAME,
            resourceUrl : 'products',
            filtersName : 'ProductsFilters',
            tableColumns: ['publisher', 'title', 'sku', 'price', 'actions'],
            allowExport : true
        };
    },

    methods: {
        onAdd() {
            this.$router.push({
                name  : PRODUCT_NAME,
                params: {
                    product: 'add'
                }
            });
        },

        onEdit(productId) {
            this.$router.push({
                name  : PRODUCT_NAME,
                params: {
                    product: productId
                }
            });
        },

        onExport() {
            window.top.location = window.baseURL + '/export/products';
        },
    }
};
