<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Customers in {{dateName}}</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group report-group">
                        <label>Month</label>
                        <vue-monthly-picker
                                v-model="month"
                                dateFormat="MMMM, YYYY"
                                inputClass="form-control m-wrap span12"
                                :monthLabels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                        />
                    </div>
                </div>
            </div>

            <hr/>

            <div v-show="isLoading" class="row loader-container">
                <spinner></spinner>
            </div>
            <div v-show="!isLoading">
                <ul class="list-group" v-if="hasData">
                    <li class="list-group-item">
                        <span class="badge">{{data.total}}</span>
                        Total customers
                    </li>
                    <li class="list-group-item">
                        <span class="badge">{{data.new}}</span>
                        New customers
                    </li>
                    <li class="list-group-item">
                        <span class="badge">{{data.returning}}</span>
                        Returning customers
                    </li>
                </ul>
                <div v-else>
                    <h2>No data found!</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import moment from 'moment';
    import VueMonthlyPicker from 'vue-monthly-picker';

    export default {
        inject: [
            'api'
        ],

        components: {
            VueMonthlyPicker
        },

        computed: {
            hasData() {
                return !!_.keys(this.data).length;
            },

            dateName() {
                return this.month.format('MMMM, YYYY');
            }
        },

        data() {
            return {
                month        : moment(),
                data         : [],
                isLoading    : false,
                selectedMonth: ''
            }
        },

        watch: {
            month: {
                immediate: true,
                handler  : 'getData'
            }
        },

        methods: {
            async getData() {
                this.isLoading = true;

                this.data = [];

                try {
                    this.data = await this.api.client.get('reports/customersAnalytics', {
                        params: {
                            date: this.month.format('YYYY-MM-DD')
                        }
                    });
                } catch (error) {
                    this.$snotify.error(error.message);
                }

                this.isLoading = false;
            },
        }
    };
</script>
