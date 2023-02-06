<template>
  <div class="modal account-add-friend" role="dialog" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Edit / Add publisher
          </h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
            <div slot="main-content">
              <ApiError :error="error"/>

              <form id="formModel" role="form" method="post" @submit.prevent="save" v-if="!isEmpty">
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
                    <InputField
                        label="Name"
                        name="firstName"
                        icon="fa-pencil-square-o"
                        v-model="model.name"
                        :errorMsg="errors.has('name') ? errors.first('name') : ''"
                        v-validate="'required'"
                        :tabindex="1"
                    />
                  </div>
                  <div class="col-lg-6">
                    <InputField
                        label="Position"
                        name="position"
                        icon="fa-pencil-square-o"
                        v-model="model.position"
                        :tabindex="2"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <croppa v-model="logo"
                            :width="200"
                            :height="100"
                            :initial-image="model.logo">
                    </croppa>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 mt-20">
                    <SaveButton :tabindex="2"/>
                  </div>
                </div>
              </form>
            </div>
          </EditPage>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import {mapActions, mapGetters} from 'vuex';
import {SET_PUBLISHER} from '../../dictionary/action-names';
import Modal from '../mixins/Modal';

export default {
  name: 'PublisherModal',

  mixins: [
    Modal
  ],

  computed: {
    ...mapGetters([
      'publishers'
    ])
  },

  data() {
    return {
      logo: {}
    };
  },

  methods: {
    ...mapActions({
      'setPublisher': SET_PUBLISHER
    }),

    createModel() {
      this.model = {
        'name': ''
      };
    },

    async loadModel() {
      this.logo = {};
      if (this.modelId === 'add') {
        this.createModel();
      } else {
        this.model = _.extend({}, this.publishers[this.modelId]);
      }

      this.isLoading = false;
    },

    afterSave(model) {
      this.setPublisher(model);
      this.$emit('saved');
    },

    transformModel() {
      if (!this.logo.imageSet) {
        throw new Error('Provide a logo for the publisher!');
      }

      return _.extend({}, this.model, {
        logo: this.logo.generateDataUrl('image/png', 0.8)
      });
    }
  }
};
</script>
