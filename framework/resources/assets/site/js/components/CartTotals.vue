<template>
    <div>
        <ul v-if="cart.discount || cart.taxRate" class="tabled-data no-border inverse-bold">
            <li>
                <label>Cart subtotal</label>
                <div class="value pull-right">${{cart.subTotal}}</div>
            </li>
            <li v-if="hasDiscount">
                <label>Discount</label>
                <div class="value pull-right">-${{cart.discount}}</div>
            </li>
            <template v-if="cart.taxRate">
                <li>
                    <label>{{cart.taxRate.name}} rate</label>
                    <div class="value pull-right">{{cart.taxRate.rate}}%</div>
                </li>
                <li>
                    <label>{{cart.taxRate.name}} amount</label>
                    <div class="value pull-right">${{cart.tax}}</div>
                </li>
            </template>
        </ul>
        <ul id="total-price" class="tabled-data inverse-bold no-border">
            <li>
                <label>Total</label>
                <div class="value pull-right">${{cart.total}}</div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: [
            'cart'
        ],

        computed: {
            hasDiscount() {
                return Number.parseFloat(this.cart.discount) > 0;
            }
        }
    };
</script>
