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
                                    <h3>Details</h3>
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
                                    <div class="form-group" :class="{'has-errors': errors.has('category')}">
                                        <InputField
                                                label="User"
                                                name="user"
                                                icon="fa-pencil-square-o"
                                                v-model="model.user"
                                                :errorMsg="errors.has('user') ? errors.first('user') : ''"
                                                :tabindex="1"
                                        />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkbox form-checkbox">
                                        <label>
                                            <input type="checkbox" v-model="model.active" tabindex="2">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <InputField
                                            label="Video URL"
                                            name="video"
                                            v-model="model.video"
                                            :errorMsg="errors.has('video') ? errors.first('video') : ''"
                                            :tabindex="3"
                                            v-validate="'url'"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Description</label>
                                    <vue-editor
                                            v-model="model.description"
                                            data-vv-name="description"
                                            v-validate="'required'"
                                    ></vue-editor>

                                    <span v-show="errors.has('description')" class="error-msg">{{ errors.first('description') }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mt-20">
                                    <SaveButton :tabindex="10"/>
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
    import {VueEditor} from "vue2-editor";
    import EditForm from '../../mixins/EditForm';
    import Route from '../../mixins/Route';
    import {TESTIMONIAL_NAME, TESTIMONIALS_NAME} from '../../../router/names';
    import {TESTIMONIALS_PATH} from '../../../router/paths';

    export default {
        inject: [
            'api'
        ],

        mixins: [
            Route,
            EditForm
        ],

        components: {
            VueEditor
        },

        data() {
            return {
                categories: [],
            };
        },

        watch: {
            '$route.params.post': {
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
            this
                .setTitle(TESTIMONIAL_NAME)
                .setBreadcrumbs([
                    {name: TESTIMONIALS_NAME, url: TESTIMONIALS_PATH},
                    {name: TESTIMONIAL_NAME}
                ]);
        },

        methods: {
            getResourceUrl(id = null) {
                return id ? `testimonials/${id}` : 'testimonials';
            },

            afterSave(model) {
                if (this.$route.params.post === 'add') {
                    this.$router.push({
                        name  : TESTIMONIAL_NAME,
                        params: {
                            post: model.id
                        }
                    });
                }
            }
        }
    };
</script>
