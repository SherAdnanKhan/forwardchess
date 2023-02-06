<template>
    <a class="close-btn" href="#" @click.prevent="removeItem">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#757575"/>
        </svg>
    </a>
</template>

<script>
    import {mapActions} from 'vuex';
    import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

    export default {
        props: [
            'product'
        ],

        mixins: [
            SnotifyWrapper,
        ],

        methods: {
            ...mapActions([
                'removeItemFromCart'
            ]),

            async removeItem() {
                try {
                    await this.removeItemFromCart(this.product);
                    // this.toDataLayer(item);
                    this.showSuccessToast('The product was removed from cart!');
                } catch (e) {
                    this.showErrorToast('There was a problem with removing the product from cart!');
                }
            },

            toDataLayer(item) {
                dataLayer.push({
                    'event'    : 'removeFromCart',
                    'ecommerce': {
                        'remove': {
                            'products': [{
                                'id'      : item.product.sku,
                                'name'    : item.product.title,
                                'price'   : item.total,
                                'brand'   : item.product.publisher,
                                'quantity': 1
                            }]
                        }
                    }
                });
            }
        }
    }
</script>
