<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Monthly orders for {{year}}</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group report-group">
                        <label>Year</label>
                        <select v-model="year" class="form-control m-wrap span12">
                            <option v-for="option in years" :value="option" :selected="year === option">
                                {{ option }}
                            </option>
                        </select>
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
                            <th>Month</th>
                            <th>Sales</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(row, key) in data">
                            <tr>
                                <th scope="row">
                                    <a href="#" @click.prevent="toggleDetails(key)">
                                        {{row.month}}
                                    </a>
                                </th>
                                <td :class="{'bold': visibleDetails[key]}">{{row.sales}}</td>
                                <td :class="{'bold': visibleDetails[key]}">${{row.amount}}</td>
                            </tr>
                            <tr v-show="visibleDetails[key]" v-for="country in row.countries">
                                <td>{{country.name}}</td>
                                <td>{{country.sales}}</td>
                                <td>${{country.amount}}</td>
                            </tr>
                        </template>
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
    import _ from 'lodash';
    import moment from 'moment';

    export default {
        inject: [
            'api'
        ],

        computed: {
            months() {
                return _.keys(this.data);
            },

            hasData() {
                return !!this.months.length;
            }
        },

        data() {
            const year = moment().year();
            const years = [];

            for (let i = 2018; i <= year + 10; i++) {
                years.push(i);
            }

            return {
                years,
                year,
                data          : {},
                visibleDetails: {},
                isLoading     : false
            }
        },

        watch: {
            year: {
                immediate: true,
                handler  : 'getData'
            }
        },

        methods: {
            async getData() {
                this.isLoading = true;

                this.data = [];

                try {
                    this.data = await this.api.client.get('reports/countrySales', {
                        params: {
                            year: this.year
                        }
                    });

                    this.visibleDetails = {};
                    _.each(this.data, (details, month) => {
                        this.visibleDetails[month] = false;
                    });
                } catch (error) {
                    this.$snotify.error(error.message);
                }

                this.isLoading = false;
            },

            toggleDetails(month) {
                this.visibleDetails = _.extend({}, this.visibleDetails, {
                    [month]: !this.visibleDetails[month]
                });
            }
        }
    };
</script>
