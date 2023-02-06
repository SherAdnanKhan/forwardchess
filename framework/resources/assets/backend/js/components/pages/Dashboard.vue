<template>
    <div>
        <PageHeader :title="meta.title"></PageHeader>

        <div class="row">
            <div class="col-md-6">
                <CountrySales/>
            </div>
            <div class="col-md-6">
                <BestSellers/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <UsersAnalytics/>
            </div>
        </div>
    </div>
</template>

<script>
    import PageHeader from '../layout/PageHeader.vue';
    import {DASHBOARD_NAME} from '../../router/names';
    import CountrySales from '../reports/CountrySales';
    import BestSellers from '../reports/BestSellers';
    import UsersAnalytics from '../reports/CustomersAnalytics';

    export default {
        components: {
            PageHeader,
            BestSellers,
            CountrySales,
            UsersAnalytics
        },

        data: () => ({
            meta: {
                title: DASHBOARD_NAME
            }
        })
    };
</script>
