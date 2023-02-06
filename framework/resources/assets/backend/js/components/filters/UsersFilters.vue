<template>
    <section class="panel filters-panel">
        <header class="panel-heading">
            <h2 class="panel-title">Filters</h2>

            <div class="panel-actions">
                <label class="display-inline mr-10">
                    <input type="checkbox" v-model="filters.trashIncluded"/>
                    <strong>Include deleted</strong>
                </label>

                <button type="button" class="btn btn-primary" @click="reset">
                    <i class="fa fa-refresh"></i>
                    Reset
                </button>
            </div>
        </header>
        <div class="panel-body">
            <div class="col-md-4">

            </div>
        </div>
    </section>
</template>

<script>
    import Filters from '../mixins/Filters';

    export default {
        mixins: [
            Filters
        ],

        methods: {
            getDefaultFilters() {
                return {
                    trashIncluded: false
                }
            },
        }
    };
</script>
