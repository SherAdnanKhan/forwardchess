<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Best sellers books for {{dateName}}</h3>
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
                <template v-if="hasData">
                    <table class="table table-striped table-report">
                        <thead>
                        <tr>
                            <th>Book</th>
                            <th>Sales</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in data">
                            <th scope="row">{{row.product}}</th>
                            <td>{{row.sales}}</td>
                            <td>${{row.amount}}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <h2>No data found!</h2>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
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
                return !!this.data.length;
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
                    this.data = await this.api.client.get('reports/bestSellers', {
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
