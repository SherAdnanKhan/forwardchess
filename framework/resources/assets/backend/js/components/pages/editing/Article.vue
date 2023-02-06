<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
      <div slot="main-content">
        <ApiError :error="error"/>

        <form
            id="formModel"
            role="form"
            method="post"
            @submit.prevent="save"
            autocomplete="off"
            v-if="!isEmpty"
        >
          <div class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12" v-if="model.id">
                  <h3>
                    Details for page
                    <a :href="model.url" class="text-info" target="_blank">`{{ model.title }}`</a>
                  </h3>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <InputField
                      label="Created at"
                      :value="model.created_at"
                      :readonly="true"
                      icon="fa-calendar"
                  />
                </div>
                <div class="col-lg-6">
                  <InputField
                      label="Updated at"
                      :value="model.updated_at"
                      :readonly="true"
                      icon="fa-calendar"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <InputField
                      label="Title"
                      name="title"
                      icon="fa-pencil-square-o"
                      v-model="model.title"
                      :errorMsg="errors.has('title') ? errors.first('title') : ''"
                      v-validate="'required'"
                      :tabindex="4"
                  />
                </div>

                <div class="col-lg-3">
                  <div class="form-group">
                    <label>Publish date</label>
                    <DatePicker
                        v-model="model.publishDate"
                        lang="en"
                        name="publishDate"
                        input-class="form-control m-wrap span12"
                        format="MMMM DD, YYYY"
                        :tabindex="10"
                    ></DatePicker>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="checkbox form-checkbox">
                    <label>
                      <input
                          type="checkbox"
                          v-model="model.active"
                          tabindex="11"
                      />
                      Active
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div
                      class="form-group"
                      :class="{ 'has-errors': errors.has('categories') }"
                  >
                    <label>Tags</label>
                    <CustomSelect
                        v-model="model.tags"
                        name="tags"
                        :options="tags"
                        :multiple="true"
                        placeholder="Select tags"
                        data-vv-value-path="selected"
                        data-vv-validate-on="input"
                        v-validate="'required'"
                        :tabindex="3"
                    />
                    <span v-show="errors.has('tags')" class="error-msg">{{
                        errors.first('tags')
                      }}</span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <Editor
                      v-model="model.content"
                      name="content"
                      api-key="no-api-key"
                      :init="{
                        height: 500,
                        plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                        ],
                        toolbar: 'undo redo | styleselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code | imagetools',
                        branding: false,
                        relative_urls: false,
                        remove_script_host: false,
                    }"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12 mt-20">
                  <SaveButton :tabindex="21"/>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </EditPage>
  </div>
</template>

<script>
import _ from 'lodash';
import moment from 'moment';
import {mapGetters} from 'vuex';
import EditForm from '../../mixins/EditForm';
import Route from '../../mixins/Route';
import {BLOG_ARTICLE_NAME, BLOG_ARTICLES_NAME} from '../../../router/names';
import {BLOG_ARTICLES_PATH} from '../../../router/paths';
import Editor from '@tinymce/tinymce-vue';

export default {
  components: {
    Editor
  },

  mixins: [Route, EditForm],

  computed: {
    ...mapGetters({
      storeTags: 'tags'
    }),

    tags() {
      return _.values(this.storeTags);
    }
  },

  data() {
    return {
      selectedTags: [],

      period   : [],
      shortcuts: [
        {
          text   : 'Today',
          onClick: () => {
            this.period = [new Date(), new Date()];
          }
        }
      ]
    };
  },

  watch: {
    '$route.params.article': {
      immediate: true,
      handler  : async function (id) {
        console.log(id);
        if (id !== 'add') {
          await this.getModel(id);
        } else {
          this.createModel();
        }
      }
    }
  },

  created() {
    this.setTitle(BLOG_ARTICLE_NAME).setBreadcrumbs([
      {name: BLOG_ARTICLES_NAME, url: BLOG_ARTICLES_PATH},
      {name: BLOG_ARTICLE_NAME}
    ]);
  },

  methods: {
    getResourceUrl(id = null) {
      return id ? `articles/${id}` : 'articles';
    },

    setModel(model) {
      this.model = model;
    },

    transformModel() {
      return _.extend({}, this.model, {
        publishDate: moment(this.model.publishDate).format('YYYY-MM-DD'),
        active     : !!this.model.active ? 1 : 0
      });
    },

    afterSave(model) {
      if (this.$route.params.article === 'add') {
        this.$router.push({
          name  : BLOG_ARTICLE_NAME,
          params: {
            article: model.id
          }
        });
      }
    }
  }
};
</script>
<style>
.tox-notifications-container {
  display: none !important;
}
</style>