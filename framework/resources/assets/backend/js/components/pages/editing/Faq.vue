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
                    <label>Category</label>
                    <CustomSelect
                        v-model="model.categoryId"
                        name="category"
                        :options="categories"
                        data-vv-value-path="selected"
                        data-vv-validate-on="input"
                        v-validate="'required'"
                        :tabindex="1"
                    />
                    <span v-show="errors.has('category')" class="error-msg">{{ errors.first('category') }}</span>
                  </div>
                </div>
                <div class="col-lg-4">
                  <InputField
                      label="Position"
                      name="position"
                      icon="fa-pencil-square-o"
                      v-model="model.position"
                      :errorMsg="errors.has('position') ? errors.first('position') : ''"
                      :tabindex="4"
                  />
                </div>

                <div class="col-lg-2">
                  <div class="checkbox form-checkbox">
                    <label>
                      <input type="checkbox" v-model="model.active" tabindex="9">
                      Active
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <TextareaField
                      label="Question"
                      name="question"
                      v-model="model.question"
                      :errorMsg="errors.has('question') ? errors.first('question') : ''"
                      v-validate="'required'"
                      :tabindex="2"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <label>Answer</label>
                  <vue-editor
                      v-model="model.answer"
                      data-vv-name="answer"
                      v-validate="'required'"
                  />

                  <span v-show="errors.has('answer')" class="error-msg">{{ errors.first('answer') }}</span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12 mt-20">
                  <SaveButton :tabindex="9"/>
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
import {VueEditor} from 'vue2-editor';
import EditForm from '../../mixins/EditForm';
import Route from '../../mixins/Route';
import {FAQ_NAME, FAQS_NAME} from '../../../router/names';
import {FAQS_PATH} from '../../../router/paths';

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
      categories: []
    };
  },

  watch: {
    '$route.params.post': {
      immediate: true,
      handler  : async function (id) {
        const promises = [
          this.getCategories()
        ];

        if (id === 'add') {
          this.createModel();
        } else {
          promises.push(this.getModel(id));
        }

        await Promise.all(promises);
      }
    }
  },

  created() {
    this
        .setTitle(FAQ_NAME)
        .setBreadcrumbs([
          {name: FAQS_NAME, url: FAQS_PATH},
          {name: FAQ_NAME}
        ]);
  },

  methods: {
    getResourceUrl(id = null) {
      return id ? `faq/${id}` : 'faq';
    },

    async getCategories() {
      try {
        this.categories = await this.api.client.get('faq/categories');
      } catch (error) {
        this.$snotify.error(error.message);
      }
    },

    afterSave(model) {
      if (this.$route.params.post === 'add') {
        this.$router.push({
          name  : FAQ_NAME,
          params: {
            post: model.id
          }
        });
      }
    }
  }
};
</script>
