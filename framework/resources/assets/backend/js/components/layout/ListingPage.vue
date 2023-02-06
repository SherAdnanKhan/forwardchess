<template>
    <div>
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <component v-if="filtersName"
                   :is="filtersName"
                   @init="setFilters"
                   @changed="applyFilters"></component>

        <component v-if="modalName && modalState"
                   :is="modalName"
                   :state="modalState"
                   :modelId="modelId"
                   :resourceUrl="resourceUrl"
                   @saved="refreshList"
                   @close="closeModal"></component>

        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Data</h2>
                <div class="panel-actions">
                    <button v-if="allowAdd" type="button" class="btn btn-primary pull-right" @click="onAdd">
                        <i class="fa fa-plus"></i>
                        Add
                    </button>

                    <button v-if="allowExport" type="button" class="btn btn-info pull-right mr-10" @click="onExport">
                        <i class="fa fa-file-excel-o"></i>
                        Export
                    </button>
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
                    <div slot="actions" slot-scope="props">
                        <a href="#" @click.prevent="onEdit(props.row.id)" class="pull-left mr-10">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>&nbsp;
                        <div v-if="allowDelete" class="pull-left">
                            <a v-if="props.row.deleted_at" href="#" @click.prevent="onRestore(props.row.id)">
                                <i class="fa fa-undo"></i>
                                Restore
                            </a>
                            <a v-else href="#" @click.prevent="onDelete(props.row.id)">
                                <i class="fa fa-remove"></i>
                                Delete
                            </a>
                        </div>
                    </div>
                </component>
            </div>
        </section>
    </div>
</template>

<script>
    import {Event} from 'vue-tables-2';
    import Route from '../mixins/Route';

    export default {
        inject: ['api'],

        mixins: [
            Route
        ],

        computed: {
            listApiUrl() {
                return `${window.apiBaseURL}/${this.resourceUrl}/tables`;
            },

            tableData() {
                return [];
            },

            table() {
                return this.$refs.table;
            }
        },

        data() {
            return {
                tableName       : 'v-server-table',
                tableColumns    : [],
                tableOptions    : {},
                tableRowsPerPage: 25,
                allowAdd        : true,
                allowExport     : false,
                allowDelete     : true,
                modalName       : null,
                modalState      : false,
                modelId         : null,
                filtersName     : null
            };
        },

        created() {
            this
                .setTitle(this.pageName)
                .setBreadcrumbs({name: this.pageName});

            this.tableOptions.perPage = this.tableRowsPerPage;
        },

        methods: {
            onAdd() {
                if (this.modalName) {
                    this.showModal('add');
                }
            },

            onExport() {
            },

            onEdit(modelId) {
                if (this.modalName) {
                    this.showModal(modelId);
                }
            },

            async onDelete(modelId) {
                if (confirm('Are you sure you want to delete this record?')) {
                    try {
                        await this.api.client.delete(`${this.resourceUrl}/${modelId}`);
                        this.refreshList();
                    } catch (error) {
                        this.$snotify.error(error.message);
                    }
                }
            },

            async onRestore(modelId) {
                try {
                    await this.api.client.put(`${this.resourceUrl}/${modelId}/restore`);
                    this.refreshList();
                } catch (error) {
                    this.$snotify.error(error.message);
                }
            },

            showModal(modelId) {
                this.modelId = modelId;
                this.modalState = true;
            },

            closeModal() {
                this.modelId = null;
                this.modalState = false;
            },

            refreshList() {
                if (this.tableName === 'v-server-table') {
                    this.table.refresh();
                }
            },

            setFilters(filters) {
                this.tableOptions.customFilters = filters;
            },

            applyFilters(name, value) {
                Event.$emit(`vue-tables.filter::${name}`, value);
            }
        }
    };
</script>