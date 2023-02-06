<template>
    <div class="qnt-holder">
        <div class="le-quantity">
            <form>
                $<input name="quantity" type="text" value="20"/>
            </form>
        </div>

        <button class="btn-add-to-cart le-button huge"
                :class="{'is-loading': isLoading}"
                :title="title"
                @click.prevent="addToCart">
            <span>buy</span>
        </button>
    </div>
</template>

<script>
    import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

    export default {
        computed: {
            title() {
                if (this.isLoading) {
                    return 'please wait...';
                } else {
                    return 'buy';
                }
            }
        },

        mixins: [
            SnotifyWrapper,
        ],

        data() {
            const presetList = [];
            for (let i = 1; i <= 5; i++) {
                const value = i * 10;
                presetList.push({value, label: `$${value}`});
            }

            presetList.push({value: 'custom', label: 'Custom'});

            return {
                presetList,
                presetValue: presetList[0].value,
                value      : 0,
                isLoading  : false,
            };
        },

        watch: {
            presetValue: {
                immediate: true,
                handler  : function (value) {
                    this.value = value;
                }
            }
        },

        methods: {
            async addToCart() {
                this.isLoading = true;

                try {
                    await this.addItemToCart(this.product);
                    this.showSuccessToast('Product added to!');

                } catch (e) {
                    this.showErrorToast('There was a problem with adding the product to cart!');
                }

                this.isLoading = false;
            }
        }
    }
</script>
