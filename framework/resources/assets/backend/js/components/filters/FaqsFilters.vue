<template>
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title display-inline">Filters</h2>

            <div class="panel-actions">
                <button type="button" class="btn btn-primary" @click="reset">
                    <i class="fa fa-refresh"></i>
                    Reset
                </button>
            </div>
        </header>
        <div class="panel-body">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Category</label>
                    <CustomSelect
                            v-model="filters.categoryId"
                            :options="categories"
                            placeholder="Pick a category"
                            name="category"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    // import _ from 'lodash';
    import Filters from '../mixins/Filters';

    export default {
        inject: [
            'api'
        ],

        mixins: [
            Filters
        ],

        data() {
            return {
                categories: []
            }
        },

        async created() {
            try {
                this.categories = await this.api.client.get('faq/categories');
            } catch (error) {
                this.$snotify.error(error.message);
            }
        },

        methods: {
            getDefaultFilters() {
                return {
                    categoryId: ''
                }
            }
        }
    };
</script>
