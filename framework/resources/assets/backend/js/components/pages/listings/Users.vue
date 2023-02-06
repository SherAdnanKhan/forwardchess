<template>
    <div>
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Data</h2>
            </header>
            <div class="panel-body">
                <component
                        :is="tableName"
                        :url="listApiUrl"
                        :data="tableData"
                        :columns="tableColumns"
                        :options="tableOptions"
                        ref="table">
                    <div slot="email" slot-scope="props">
                        <a href="#" @click.prevent="onEdit(props.row.id)" class="pull-left mr-10">{{props.row.email}}</a>
                    </div>

                    <div slot="actions" slot-scope="props">
                        <a href="#" @click.prevent="onEdit(props.row.id)" class="pull-left mr-10">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>&nbsp;
                        <div v-if="canDelete" class="pull-left">
                            <a href="#" @click.prevent="onDelete(props.row.id)">
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
    import {mapGetters} from "vuex";
    import ListingPage from '../../layout/ListingPage.vue';
    import {USER_NAME, USERS_NAME} from '../../../router/names';

    export default {
        extends: ListingPage,

        data() {
            return {
                pageName    : USERS_NAME,
                resourceUrl : 'users',
                tableColumns: ['email', 'firstName', 'lastName', 'actions'],
                allowAdd    : false
            };
        },

        computed: {
            ...mapGetters([
                'user'
            ]),

            canDelete() {
                return !!this.user.superAdmin;
            }
        },

        methods: {
            onEdit(userId) {
                this.$router.push({
                    name  : USER_NAME,
                    params: {
                        user: userId
                    }
                });
            }
        }
    }
</script>
