<template>
    <button :class="{
                'button': true,
                'le-button': true,
                'is-loading': isLoading,
                'disabled':isDisabled,
                'd-flex' :true,
                'align-items-center' :true,
            }"
            :title="title"
            :disabled="isDisabled"
            @click.prevent="addToCart">
        <span class="icon m-r-10  d-flex align-items-center">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.2 11.2C3.43 11.2 2.807 11.83 2.807 12.6C2.807 13.37 3.43 14 4.2 14C4.97 14 5.6 13.37 5.6 12.6C5.6 11.83 4.97 11.2 4.2 11.2ZM0 0V1.4H1.4L3.92 6.713L2.975 8.428C2.863 8.624 2.8 8.855 2.8 9.1C2.8 9.87 3.43 10.5 4.2 10.5H12.6V9.1H4.494C4.396 9.1 4.319 9.023 4.319 8.925L4.34 8.841L4.97 7.7H10.185C10.71 7.7 11.172 7.413 11.41 6.979L13.916 2.436C13.972 2.338 14 2.219 14 2.1C14 1.715 13.685 1.4 13.3 1.4H2.947L2.289 0H0ZM11.2 11.2C10.43 11.2 9.807 11.83 9.807 12.6C9.807 13.37 10.43 14 11.2 14C11.97 14 12.6 13.37 12.6 12.6C12.6 11.83 11.97 11.2 11.2 11.2Z"
                      fill="white"/>
            </svg>
        </span>
        <span class="d-flex align-items-center">{{ text }}</span>
    </button>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

    export default {
        props : {
            product: {
                type    : String,
                required: true
            },

            denyBuy: {
                type   : String,
                default: '0'
            },

            bought: {
                type   : String,
                default: ''
            },

            section: {
                type   : String,
                default: ''
            }
        },
        mixins: [
            SnotifyWrapper,
        ],

        computed: {
            ...mapGetters([
                'cartItemsIds',
                'cartAction'
            ]),

            blockBuy() {
                return parseInt(this.denyBuy) > 0;
            },

            isBought() {
                return !!this.bought;
            },

            inCart() {
                return this.cartItemsIds.includes(Number(this.product));
            },

            isDisabled() {
                return this.isBought || this.blockBuy || this.cartAction || this.inCart;
            },

            title() {
                if (this.isBought) {
                    return 'Already bought';
                } else if (this.blockBuy) {
                    return 'Not available';
                } else if (this.inCart) {
                    return 'In Cart';
                } else if (this.cartAction) {
                    return 'please wait...';
                } else {
                    return 'add product to cart';
                }
            },

            text() {
                if (this.isBought) {
                    return 'Already bought';
                } else if (this.inCart) {
                    return 'In cart';
                } else if (this.blockBuy) {
                    return 'Not yet available';
                } else {
                    return 'add to cart';
                }
            }
        },

        data: () => ({
            isLoading: false
        }),

        methods: {
            ...mapActions([
                'addItemToCart'
            ]),

            async addToCart() {
                this.isLoading = true;

                try {
                    await this.addItemToCart({ productId: this.product, section : this.section });
                    // this.toDataLayer(item);
                    this.showSuccessToast('Product added to cart!');
                } catch (e) {
                    this.showErrorToast('There was a problem with adding the product to cart!');

                }


                this.isLoading = false;
            },

            toDataLayer(item) {
                dataLayer.push({
                    'event'    : 'addToCart',
                    'ecommerce': {
                        'currencyCode': 'USD',
                        'add'         : {
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


