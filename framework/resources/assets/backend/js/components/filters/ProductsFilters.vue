<template>
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title display-inline">Filters</h2>

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
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Publisher</label>
                    <CustomSelect
                            v-model="filters.publisherId"
                            :options="publishers"
                            placeholder="Pick a publisher"
                            name="publisher"
                    />
                </div>
            </div>
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
    import _ from 'lodash';
    import {mapGetters} from 'vuex';
    import Filters from '../mixins/Filters';

    export default {
        mixins: [
            Filters
        ],

        computed: {
            ...mapGetters({
                'storePublishers': 'publishers',
                'storeCategories': 'categories'
            }),

            publishers() {
                return _.values(this.storePublishers);
            },

            categories() {
                return _.values(this.storeCategories);
            }
        },

        methods: {
            getDefaultFilters() {
                return {
                    publisherId  : '',
                    categoryId   : '',
                    trashIncluded: false
                }
            }
        }
    };
</script>
