<template>
    <div>
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
            <div slot="main-content">
                <ApiError :error="error"/>

                <div class="panel" v-if="!isEmpty">
                    <form id="formModel" role="form" method="post" @submit.prevent="save">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>
                                        Details for user <span class="text-info">`{{model.email}}`</span>
                                        <a v-if="!model.emailConfirmed" href="#" class="btn btn-default" @click.prevent="activateEmail">Activate email</a>
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField label="Created at" :value="model.created_at" :readonly="true" icon="fa-calendar"/>
                                </div>
                                <div class="col-lg-6">
                                    <InputField label="Updated at" :value="model.updated_at" :readonly="true" icon="fa-calendar"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField label="Last login" :value="model.lastLogin" :readonly="true" icon="fa-calendar"/>
                                </div>
                                <div class="col-lg-6">
                                    <InputField label="Email" :value="model.email" icon="fa-envelope" :readonly="true"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField
                                            label="First name"
                                            name="firstName"
                                            icon="fa-pencil-square-o"
                                            v-model="model.firstName"
                                            :errorMsg="errors.has('firstName') ? errors.first('firstName') : ''"
                                            v-validate="'required'"
                                            :tabindex="2"
                                    />
                                </div>
                                <div class="col-lg-6">
                                    <InputField
                                            label="Last name"
                                            name="lastName"
                                            icon="fa-pencil-square-o"
                                            v-model="model.lastName"
                                            :errorMsg="errors.has('lastName') ? errors.first('lastName') : ''"
                                            v-validate="'required'"
                                            :tabindex="2"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-20">
                                    <SaveButton :tabindex="3"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </EditPage>
    </div>
</template>

<script>
    import _ from 'lodash';
    import {mapActions, mapGetters} from 'vuex';
    import {SET_USER} from '../../../dictionary/action-names';
    import EditForm from '../../mixins/EditForm';
    import Route from '../../mixins/Route';
    import {USER_NAME, USERS_NAME} from '../../../router/names';
    import {USERS_PATH} from '../../../router/paths';

    export default {
        mixins: [
            Route,
            EditForm
        ],

        computed: {
            ...mapGetters([
                'user'
            ])
        },

        watch: {
            '$route.params.user': {
                immediate: true,
                handler  : async function (id) {
                    await this.getModel(id);
                }
            }
        },

        created() {
            this
                .setTitle(USER_NAME)
                .setBreadcrumbs([
                    {name: USERS_NAME, url: USERS_PATH},
                    {name: USER_NAME}
                ]);
        },

        methods: {
            ...mapActions({
                'setUser': SET_USER
            }),

            getResourceUrl(id) {
                return `users/${id}`;
            },

            afterSave(model) {
                if (model.id === this.user.id) {
                    this.setUser({
                        user: _.extend({}, model)
                    });
                }
            },

            async activateEmail() {
                this.isLoading = true;

                try {
                    this.model = await this.api.client.post(`users/activate/${this.model.id}`)
                } catch (error) {
                    this.error = error;
                }

                this.isLoading = false;
            }
        }
    };
</script>
