<template>
  <div class="edit-page">
    <section class="panel">
      <header class="panel-heading">
        <h2 class="panel-title">{{ review.title }}</h2>
      </header>

      <ApiError :error="error"/>

      <form role="form" method="post" @submit.prevent="save" autocomplete="off">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-6">
              <InputField label="Created at" :value="review.created_at" :readonly="true" icon="fa-calendar"/>
            </div>
            <div class="col-lg-6">
              <InputField label="Updated at" :value="review.updated_at" :readonly="true" icon="fa-calendar"/>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <InputField label="Nickname" :value="review.nickname" :readonly="true" icon="fa-info"/>
            </div>
            <div class="col-lg-6">
              <InputField label="Product" :value="review.productName" :readonly="true" icon="fa-info"/>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <InputField
                  label="Title"
                  name="title"
                  icon="fa-pencil-square-o"
                  v-model="form.title"
                  :errorMsg="errors.has('title') ? errors.first('title') : ''"
                  v-validate="'required'"
              />
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <TextareaField
                  label="Review"
                  name="review"
                  v-model="form.description"
                  :errorMsg="errors.has('description') ? errors.first('description') : ''"
                  v-validate="'required'"
              />
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              Approved
              <toggle-button
                  v-model="form.approved"
                  :labels="{checked: 'Yes', unchecked: 'No'}"
              />
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 mt-20">
              <SaveButton :tabindex="21"/>
            </div>
          </div>
        </div>
      </form>
    </section>
  </div>
</template>

<script>
import InputField from '../layout/InputField';
import TextareaField from '../layout/TextareaField';
import SaveButton from '../layout/SaveButton';
import ApiError from '../../../../common/components/ApiError';
import {ToggleButton} from 'vue-js-toggle-button';

export default {
  name: 'ReviewsDetails',

  inject: [
    'api'
  ],

  components: {
    ApiError,
    InputField,
    TextareaField,
    ToggleButton,
    SaveButton
  },

  props: {
    review: {
      type    : Object,
      required: true
    }
  },

  data: () => ({
    error: null,
    form : {
      title      : null,
      description: null,
      approved   : null
    }
  }),

  computed: {
    apiUrl() {
      return `${window.apiBaseURL}/reviews/${this.review.id}`;
    }
  },

  watch: {
    review: {
      immediate: true,
      handler(value) {
        this.form = {
          title      : value.title,
          description: value.description,
          approved   : value.approved
        };
      }
    }
  },

  methods: {
    async save() {
      const isValid = await this.$validator.validateAll();
      if (!isValid) {
        return;
      }

      this.isLoading = true;
      this.error = null;

      try {
        await this.api.client.put(this.apiUrl, {...this.form, approved: this.form.approved ? 1 : 0});
        this.$emit('changed');
      } catch (error) {
        this.error = error;
      }

      this.isLoading = false;
    }
  }
};
</script>