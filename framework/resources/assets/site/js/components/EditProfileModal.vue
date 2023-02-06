<template>
  <div class="modal fade edit-form profile-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-title d-flex justify-content-between">
          Edit Your information
          <button type="button" class="close" data-dismiss="modal">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M15.5314 2.73138C16.1562 2.10653 16.1562 1.09347 15.5314 0.46863C14.9066 -0.15621 13.8935 -0.15621 13.2686 0.46863L8.00001 5.73727L2.73138 0.46863C2.10653 -0.15621 1.09347 -0.15621 0.46863 0.46863C-0.15621 1.09347 -0.15621 2.10653 0.46863 2.73138L5.73727 8.00001L0.46863 13.2686C-0.15621 13.8935 -0.15621 14.9066 0.46863 15.5314C1.09347 16.1562 2.10653 16.1562 2.73138 15.5314L8.00001 10.2627L13.2686 15.5314C13.8935 16.1562 14.9066 16.1562 15.5314 15.5314C16.1562 14.9066 16.1562 13.8935 15.5314 13.2686L10.2628 8.00001L15.5314 2.73138Z"
                    fill="#757575"/>
            </svg>
          </button>
        </div>

        <section class="modal-body" :class="{'is-loading': isLoading}">
          <div class="mask"></div>
          <div class="spinner"></div>

          <api-error :error="error"/>

          <div class="m-b-20 wrapper-error">
            <label for="email" class="description m-b-10">Your Email</label>
            <input
                id="email"
                type="email"
                v-model="form.email"
                class="le-input"
                :class="{'le-input': true, 'is-invalid': errors.has('email')}"
                name="email"
                disabled="disabled"
                readonly="readonly">
          </div>

          <div class="m-b-20 wrapper-error">
            <label class="description m-b-10" for="firstName">First name *</label>
            <input
                type="text"
                id="firstName"
                name="firstName"
                class="modal-input"
                :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('firstName')}"
                v-validate="'required|max:255'"
                v-model="form.firstName"
                data-vv-as="first name">
            <span v-show="errors.has('firstName')" class="invalid-feedback">
                <strong>{{ errors.first('firstName') }}</strong>
            </span>
          </div>

          <div class="m-b-20 wrapper-error">
            <label class="description m-b-10" for="lastName">Last name *</label>
            <input
                type="text"
                id="lastName"
                name="lastName"
                class="modal-input"
                :class="{'le-input': true, 'capital': true, 'is-invalid': errors.has('lastName')}"
                v-validate="'required|max:255'"
                v-model="form.lastName"
                data-vv-as="last name"
            >
            <span v-show="errors.has('lastName')" class="invalid-feedback">
                <strong>{{ errors.first('lastName') }}</strong>
            </span>
          </div>

          <div class="m-b-20 wrapper-error">
            <label class="description m-b-10" for="lastName">Nickname</label>
            <input
                type="text"
                id="nickname"
                name="nickname"
                class="modal-input"
                :class="{'le-input': true, 'is-invalid': errors.has('nickname')}"
                v-validate="'required|max:100'"
                v-model="form.nickname"
            >
            <span v-show="errors.has('nickname')" class="invalid-feedback">
                <strong>{{ errors.first('nickname') }}</strong>
            </span>
          </div>

          <div class="d-flex">
            <input
                id="subscribed"
                type="checkbox"
                name="subscribed"
                value="1"
                v-model="form.subscribed"
            >
            <label for="subscribed" class="m-l-10">
              I agree to ForwardChess.com keeping me updated with news, features, and special offers.
            </label>
          </div>
        </section>
        <div class="modal-footer">
          <div class="buttons-holder">
            <button type="submit" class="le-button" :class="{'disabled': isLoading}" @click="saveData" :disabled="isLoading">Save changes</button>
          </div><!-- /.buttons-holder -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import SnotifyWrapper from '../mixins/SnotifyWrapper';

export default {
  name: 'EditProfileModal',

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
    },

    profile: {
      type   : String,
      default: JSON.stringify({})
    }
  },

  data() {
    const profile = JSON.parse(this.profile);

    return {
      isLoading: false,
      error    : null,
      form     : this.getFormData(profile)
    };
  },

  methods: {
    getFormData(data) {
      const getValue = (property) => {
        return data.hasOwnProperty(property) ? data[property] : '';
      };

      return {
        email     : getValue('email'),
        firstName : getValue('firstName'),
        lastName  : getValue('lastName'),
        nickname  : getValue('nickname'),
        subscribed: getValue('subscribed')
      };
    },

    async saveData() {
      const success = await this.$validator.validate();
      if (!success) {
        return;
      }

      this.isLoading = true;
      const saveData = _.omit(this.form, ['email']);

      try {
        const profile = await this.api.client.put(this.formAction, {
          ...saveData,
          subscribed: saveData.subscribed ? 1 : 0
        });
        this.form = this.getFormData(profile);
        this.showSuccessToast('Profile data was saved.');
      } catch (error) {
        this.showErrorToast('There was a problem with saving your data.');
        this.error = error;
      }

      this.isLoading = false;
    }
  }
};
</script>
