<template>
    <div>
        <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

        <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
            <div slot="main-content">
                <ApiError :error="error"/>

                <div class="panel" v-if="!isEmpty">
                    <form id="formModel" role="form" method="post" @submit.prevent="save" autocomplete="off">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12" v-if="model.id">
                                    <h3>Details for gift cards <span class="text-info">`{{model.code}}`</span>({{model.enabled ? 'enabled' : 'disabled'}})</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <InputField label="Created at" :value="model.created_at" :readonly="true" icon="fa-calendar"/>
                                </div>
                                <div class="col-lg-4">
                                    <InputField label="Updated at" :value="model.updated_at" :readonly="true" icon="fa-calendar"/>
                                </div>
                                <div class="col-lg-4">
                                    <InputField label="Expires at" :value="model.expireDate" :readonly="true" icon="fa-calendar"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField label="Buyer" name="userName" icon="fa-info" :readonly="true" :value="model.buyer"/>
                                </div>
                                <div class="col-lg-6">
                                    <InputField label="Code" name="code" icon="fa-info" :readonly="true" :value="model.code"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField label="Original amount" name="originalAmount" icon="fa-dollar" :readonly="true" :value="model.originalAmount"/>
                                </div>
                                <div class="col-lg-6">
                                    <InputField
                                            label="Current amount"
                                            name="amount"
                                            icon="fa-dollar"
                                            :readonly="isReadonly"
                                            v-model="model.amount"
                                            :errorMsg="errors.has('amount') ? errors.first('amount') : ''"
                                            v-validate="{ required: true, numeric: true, min_value: 1 }"
                                            :tabindex="2"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <InputField
                                            label="Friend name"
                                            name="friendName"
                                            icon="fa-pencil-square-o"
                                            :readonly="isReadonly"
                                            v-model="model.friendName"
                                            :errorMsg="errors.has('friendName') ? errors.first('friendName') : ''"
                                            v-validate="{ required: true }"
                                            data-vv-as="friend name"
                                            :tabindex="3"
                                    />
                                </div>
                                <div class="col-lg-6">
                                    <InputField
                                            label="Friend email"
                                            name="friendEmail"
                                            icon="fa-pencil-square-o"
                                            :readonly="isReadonly"
                                            v-model="model.friendEmail"
                                            :errorMsg="errors.has('friendEmail') ? errors.first('friendEmail') : ''"
                                            v-validate="{ required: true, email: true }"
                                            data-vv-as="friend email"
                                            :tabindex="4"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <TextareaField label="Friend message" name="friendMessage" :readonly="isReadonly" v-model="model.friendMessage" :tabindex="5"/>
                                </div>
                            </div>

                            <div class="row" v-if="!isReadonly">
                                <div class="col-lg-12 mt-20">
                                    <SaveButton :tabindex="10" label="Create"/>
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
    import EditForm from '../../mixins/EditForm';
    import Route from '../../mixins/Route';
    import {GIFT_NAME, GIFTS_NAME} from '../../../router/names';
    import {GIFTS_PATH} from '../../../router/paths';

    export default {
        mixins: [
            Route,
            EditForm
        ],

        computed: {
            isReadonly() {
                return !!(this.model && this.model.id);
            },
        },

        watch: {
            '$route.params.gift': {
                immediate: true,
                handler  : async function (id) {
                    if (id === 'add') {
                        this.createModel();
                    } else {
                        await this.getModel(id);
                    }
                }
            }
        },

        created() {
            this.setTitle(GIFT_NAME)
                .setBreadcrumbs([
                    {name: GIFTS_NAME, url: GIFTS_PATH},
                    {name: GIFT_NAME}
                ]);
        },

        methods: {
            getResourceUrl(id = null) {
                return id ? `gifts/${id}` : 'gifts';
            },

            transformModel() {
                return _.extend({}, this.model, {
                    enabled: 1
                });
            },

            afterSave(model) {
                if (this.$route.params.gift === 'add') {
                    this.$router.push({
                        name  : GIFT_NAME,
                        params: {
                            gift: model.id
                        }
                    });
                }
            }
        }
    };
</script>
