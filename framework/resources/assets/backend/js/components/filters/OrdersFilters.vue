<template>
    <section class="panel filters-panel">
        <header class="panel-heading">
            <h2 class="panel-title">Filters</h2>
            <div class="panel-actions">
                <button type="button" class="btn btn-primary pull-right" @click="reset">
                    <i class="fa fa-refresh"></i>
                    Reset
                </button>
            </div>
        </header>
        <div class="panel-body">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Status</label>
                    <CustomSelect
                            v-model="filters.status"
                            :options="statuses"
                            placeholder="Pick a status"
                            name="status"
                    />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>Period</label>
                    <date-picker
                            range
                            v-model="filters.period"
                            :shortcuts="shortcuts"
                            lang="en"
                            input-class="form-control m-wrap span12"></date-picker>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import moment from 'moment';
    import Filters from '../mixins/Filters';
    import orderStatuses from '../../dictionary/order-statuses';

    export default {
        mixins: [
            Filters
        ],

        data() {
            return {
                statuses : orderStatuses,
                shortcuts: [
                    {
                        text   : 'Today',
                        onClick: () => {
                            const today = new Date();

                            this.filters.period = [today, today]
                        }
                    },
                    {
                        text   : 'This week',
                        onClick: () => {
                            const start = moment().startOf('isoWeek').toDate(),
                                  end   = moment().endOf('isoWeek').toDate();

                            this.filters.period = [start, end]
                        }
                    },
                    {
                        text   : 'This month',
                        onClick: () => {
                            const start = moment().startOf('month').toDate();

                            this.filters.period = [start, new Date()]
                        }
                    },
                    {
                        text   : 'This year',
                        onClick: () => {
                            const start = moment().startOf('year').toDate();

                            this.filters.period = [start, new Date()]
                        }
                    }
                ],
            };
        },

        methods: {
            getDefaultFilters() {
                return {
                    status       : '',
                    period       : [],
                    trashIncluded: false
                }
            },

            getFilter(name, value) {
                switch (name) {
                    case 'period':
                        const format = (date) => moment(date).format('YYYY-MM-DD');

                        return [format(value[0]), format(value[1])];
                }

                return value;
            }
        }
    };
</script>
