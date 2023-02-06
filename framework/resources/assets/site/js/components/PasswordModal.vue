<template>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-wrap">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="wrap-btn-hide p-t-30 m-l-20">
          <button type="button" class="btn-hide" data-dismiss="modal">
            <svg class="m-r-10" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 5.24267V6.75782H2.90909L7.07576 10.9245L6 12.0002L0 6.00024L6 0.000244141L7.07576 1.076L2.90909 5.24267H12Z" fill="#F96F34"/>
            </svg>
            Back
          </button>
        </div>
        <div class="modal-title d-flex justify-content-between">
          Change password
          <button type="button" class="close" data-dismiss="modal">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M15.5314 2.73138C16.1562 2.10653 16.1562 1.09347 15.5314 0.46863C14.9066 -0.15621 13.8935 -0.15621 13.2686 0.46863L8.00001 5.73727L2.73138 0.46863C2.10653 -0.15621 1.09347 -0.15621 0.46863 0.46863C-0.15621 1.09347 -0.15621 2.10653 0.46863 2.73138L5.73727 8.00001L0.46863 13.2686C-0.15621 13.8935 -0.15621 14.9066 0.46863 15.5314C1.09347 16.1562 2.10653 16.1562 2.73138 15.5314L8.00001 10.2627L13.2686 15.5314C13.8935 16.1562 14.9066 16.1562 15.5314 15.5314C16.1562 14.9066 16.1562 13.8935 15.5314 13.2686L10.2628 8.00001L15.5314 2.73138Z"
                    fill="#757575"/>
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <api-error :error="error"></api-error>

          <section>
            <div>
              <div class="p-b-20 wrapper-error">
                <label for="current-password" class="description m-b-10">Current password</label>
                <input id="current-password"
                       v-model="form.currentPassword"
                       type="password"
                       name="currentPassword"
                       v-validate="'required|min:8'"
                       data-vv-as="current password"
                       :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('currentPassword')}">

                <span v-show="errors.has('currentPassword')" class="invalid-feedback">
                    {{ errors.first('currentPassword') }}
                </span>
              </div>
              <div class="p-b-20 wrapper-error">
                <label for="password" class="description m-b-10">New password</label>
                <input id="password"
                       v-model="form.password"
                       type="password"
                       v-validate="'required|min:8'"
                       name="password"
                       ref="password"
                       data-vv-as="new password"
                       :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('password')}">

                <span v-show="errors.has('password')" class="invalid-feedback">
                    {{ errors.first('password') }}
                </span>
              </div>
              <div class="p-b-20 wrapper-error">
                <label for="repeated-password" class="description m-b-10">Confirm new password</label>
                <input id="repeated-password"
                       v-model="form.password_confirmation"
                       type="password"
                       v-validate="'required|confirmed:password|min:8'"
                       name="password_confirmation"
                       data-vv-as="confirm password"
                       :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('password_confirmation')}">

                <span v-show="errors.has('password_confirmation')" class="invalid-feedback">
                    {{ errors.first('password_confirmation') }}
                </span>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <div class="buttons-holder">
            <button type="submit" class="le-button" @click="saveData" :disabled="isLoading">Save changes</button>
          </div><!-- /.buttons-holder -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SnotifyWrapper from '../mixins/SnotifyWrapper';

export default {
  name: 'PasswordModal',

  inject: [
    'api'
  ],

  mixins: [
    SnotifyWrapper
  ],

  props: {
    formAction: {
      type    : String,
      required: true
    }
  },

  data() {
    return {
      isLoading: false,
      error    : null,

      form: {
        currentPassword      : null,
        password             : null,
        password_confirmation: null
      }
    };
  },

  methods: {
    async saveData() {
      const success = await this.$validator.validate();
      if (!success) {
        return;
      }

      this.isLoading = true;

      try {
        await this.api.client.post(this.formAction, this.form);
        this.showSuccessToast('Password was saved.');
        this.resetForm();
      } catch (error) {
        this.showErrorToast('There was a problem with saving your data.');
        this.error = error;
      }

      this.isLoading = false;
    },

    resetForm() {
      this.form = {
        currentPassword      : null,
        password             : null,
        password_confirmation: null
      };
    }
  }
};
</script>

<style scoped>

</style>
