<template>
  <div class="modal account-add-friend" role="dialog" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Edit tax rate
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
                        label="Country code"
                        name="code"
                        v-model="model.code"
                        :readonly="true"
                    />
                  </div>

                  <div class="col-lg-6">
                    <InputField
                        label="Country name"
                        name="country"
                        icon="fa-pencil-square-o"
                        v-model="model.country"
                        :errorMsg="errors.has('country') ? errors.first('country') : ''"
                        v-validate="'required'"
                        data-vv-as="country name"
                        :tabindex="1"
                    />
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <InputField
                        label="Rate"
                        name="rate"
                        icon="fa-pencil-square-o"
                        v-model="model.rate"
                        :errorMsg="errors.has('rate') ? errors.first('rate') : ''"
                        v-validate="'required|decimal:2'"
                        :tabindex="2"
                    />
                  </div>

                  <div class="col-lg-6">
                    <InputField
                        label="Name"
                        name="name"
                        icon="fa-pencil-square-o"
                        v-model="model.name"
                        :errorMsg="errors.has('name') ? errors.first('name') : ''"
                        v-validate="'required'"
                        :tabindex="3"
                    />
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
import Modal from '../mixins/Modal';

export default {
  name: 'TaxRateModal',

  mixins: [
    Modal
  ],

  methods: {
    getResourceUrl(code = null) {
      return code ? `tax-rates/${code}` : 'tax-rates';
    },

    createModel() {
      this.model = {
        country: null,
        rate   : null,
        name   : null
      };
    },

    async save() {
      const isValid = await this.$validator.validateAll();
      if (!isValid) {
        return;
      }

      this.isLoading = true;
      this.error = null;

      try {
        const model = await this.api.client.put(this.getResourceUrl(this.model.code), this.transformModel());

        this.setModel(model);
        this.afterSave(this.model);
      } catch (error) {
        this.error = error;
      }

      this.isLoading = false;
    }
  }
};
</script>
