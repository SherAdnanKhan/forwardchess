<template>
    <div class="order-page edit-page">
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <!--<div class="tabs tabs-primary">
            <ul class="nav nav-tabs text-right">
                <li :class="{'active': generalActive}">
                    <a href="#" @click.prevent="setActiveTab(1)"><i class="fa fa-star"></i> General info</a>
                </li>
                <li :class="{'active': billingActive}">
                    <a href="#" @click.prevent="setActiveTab(2)">Billing</a>
                </li>

                <li :class="{'active': paymentActive}">
                    <a href="#" @click.prevent="setActiveTab(3)">Payment</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': generalActive}">
                    <GeneralTab :orderId="orderId" :visible="generalActive"></GeneralTab>
                </div>

                <div class="tab-pane" :class="{'active': billingActive}">
                    <BillingTab :orderId="orderId" :visible="billingActive"></BillingTab>
                </div>

                <div class="tab-pane" :class="{'active': paymentActive}">
                    <PaymentTab :orderId="orderId" :visible="paymentActive"></PaymentTab>
                </div>
            </div>
        </div>-->

        <div v-if="isLoading" class="row loader-container">
            <spinner></spinner>
            <h3 class="text-center">Loading data...</h3>
        </div>
        <div v-else>
            <ApiError :error="error"/>

            <div v-if="hasData" class="container">
                <div class="row">
                    <div class="col-md-6">
                        <GeneralPanel :model="order.general"/>
                    </div>
                    <div class="col-md-6">
                        <BillingPanel :model="order.billing"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ItemsPanel :model="order.general" :items="order.items"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Route from '../../mixins/Route';
    import {ORDER_NAME, ORDERS_NAME} from '../../../router/names';
    import {ORDERS_PATH} from '../../../router/paths';
    import ApiError from '../../../../../common/components/ApiError.vue';
    import GeneralPanel from '../../order/Panels/GeneralPanel';
    import BillingPanel from '../../order/Panels/BillingPanel';
    import ItemsPanel from '../../order/Panels/ItemsPanel';
    /* import GeneralTab from '../order/Tabs/GeneralTab';
     import BillingTab from '../order/Tabs/BillingTab';
     import PaymentTab from '../order/Tabs/PaymentTab';*/

    export default {
        inject: ['api'],

        mixins: [
            Route
        ],

        components: {
            ApiError,
            GeneralPanel,
            BillingPanel,
            ItemsPanel
        },

        computed: {
            orderId() {
                return this.$route.params.order;
            },

            /*generalActive() {
                return (this.activeTab === 1);
            },

            billingActive() {
                return (this.activeTab === 2);
            },

            paymentActive() {
                return (this.activeTab === 3);
            }*/
        },

        data() {
            return {
                isLoading: true,
                error    : null,
                hasData  : false,
                order    : {
                    general: null,
                    billing: null,
                    payment: null,
                    items  : null
                }
            }
        },

        created() {
            this
                .setTitle(`${ORDER_NAME} #${this.orderId}`)
                .setBreadcrumbs([
                    {name: ORDERS_NAME, url: ORDERS_PATH},
                    {name: ORDER_NAME}
                ]);

            if (!this.orderId) {
                this.$router.push({
                    name: ORDERS_NAME
                });
            }

            this.loadOrder();
        },

        methods: {
            async loadOrder() {
                this.isLoading = true;
                this.hasData = false;

                try {
                    const baseUrl = `orders/${this.orderId}`,
                          data    = await Promise.all([
                              this.api.client.get(baseUrl),
                              this.api.client.get(`${baseUrl}/billing`),
                              this.api.client.get(`${baseUrl}/items`),
                              // this.api.client.get(`${baseUrl}/payment`)
                          ]);

                    this.order.general = data[0];
                    this.order.billing = data[1];
                    this.order.items = data[2];

                    this.hasData = true;
                } catch (error) {
                    this.error = error;
                }

                this.isLoading = false;
            }
        }
    };
</script>
