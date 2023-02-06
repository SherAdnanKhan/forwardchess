<template>
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">Items</h2>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb-none">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Unit price</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in items">
                        <td>{{index + 1}}</td>
                        <td>
                            <a href="#" @click.prevent="goToProduct(item.productId)">{{item.name}}</a>
                        </td>
                        <td>${{item.unitPrice}}</td>
                        <td>${{item.total}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="summary-label">Subtotal</td>
                        <td class="summary-value">${{model.subTotal}}</td>
                    </tr>
                    <tr class="b-top-none">
                        <td colspan="3" class="summary-label">Discount</td>
                        <td class="summary-value">${{model.discount}}</td>
                    </tr>
                    <tr class="b-top-none">
                        <td colspan="3" class="summary-label">Tax</td>
                        <td class="summary-value">${{model.taxAmount}}</td>
                    </tr>
                    <tr class="h4">
                        <td colspan="3" align="right">Total</td>
                        <td class="text-left">${{model.total}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

<script>
    import {PRODUCT_NAME} from '../../../router/names';

    export default {
        props: {
            model: {
                type    : Object,
                required: true
            },

            items: {
                type    : Array,
                required: true
            }
        },

        methods: {
            goToProduct(productId) {
                this.$router.push({
                    name  : PRODUCT_NAME,
                    params: {
                        product: String(productId)
                    }
                });
            }
        }
    };
</script>
