<template>
    <div>
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <OrdersFilters @init="setFilters" @changed="applyFilters"></OrdersFilters>

        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Data</h2>
                <div class="panel-actions">
                    <button type="button" class="btn btn-info pull-right" @click="onExport">
                        <i class="fa fa-file-excel-o"></i>
                        Export
                    </button>

                    <div class="form-group report-group pull-right mr-10">
                        <label>Month</label>
                        <vue-monthly-picker
                                v-model="month"
                                dateFormat="MMMM, YYYY"
                                inputClass="form-control m-wrap span12"
                                :monthLabels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                        />
                    </div>
                </div>
            </header>
            <div class="panel-body">
                <component
                        :is="tableName"
                        :url="listApiUrl"
                        :data="tableData"
                        :columns="tableColumns"
                        :options="tableOptions"
                        ref="table">
                    <div slot="id" slot-scope="props">
                        <a href="#" @click.prevent="onEdit(props.row.id)" class="pull-left mr-10">#{{props.row.id}}</a>
                    </div>
                    <div slot="email" slot-scope="props">
                        <a href="#" @click.prevent="goToUser(props.row.userId)" class="pull-left mr-10">{{props.row.email}}</a>
                    </div>
                    <div slot="total" slot-scope="props">
                        ${{props.row.total}}
                    </div>
                  <div slot="child_row" slot-scope="props">
                    use this pattern to list the review details
                  </div>
                </component>
            </div>
        </section>
    </div>
</template>

<script>
    import moment from 'moment';
    import ListingPage from '../../layout/ListingPage.vue';
    import OrdersFilters from '../../filters/OrdersFilters';
    import VueMonthlyPicker from 'vue-monthly-picker';
    import {ORDER_NAME, ORDERS_NAME, USER_NAME} from '../../../router/names';

    export default {
        extends: ListingPage,

        components: {
            OrdersFilters,
            VueMonthlyPicker
        },

        data() {
            return {
                pageName    : ORDERS_NAME,
                resourceUrl : 'orders',
                filtersName : 'OrdersFilters',
                tableColumns: ['created_at', 'id', 'status', 'email', 'fullName', 'paymentMethod', 'total', 'refNo'],
                tableOptions: {
                    headings  : {
                        created_at   : 'Created at',
                        fullName     : 'Name',
                        paymentMethod: 'Payment',
                        refNo        : 'Reference'
                    },
                    filterable: ['id', 'email', 'fullName'],
                    orderBy   : {
                        column   : 'created_at',
                        ascending: false
                    }
                },

                month: moment(),
            };
        },

        methods: {
            onEdit(orderId) {
                this.$router.push({
                    name  : ORDER_NAME,
                    params: {
                        order: String(orderId)
                    }
                });
            },

            goToUser(userId) {
                this.$router.push({
                    name  : USER_NAME,
                    params: {
                        user: String(userId)
                    }
                });
            },

            onExport() {
                window.top.location = window.baseURL + '/export/orders/?month=' + this.month.format('YYYY-MM');
            },
        }
    };
</script>