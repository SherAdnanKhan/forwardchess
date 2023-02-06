<template>
  <div class="modal fade edit-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-title d-flex justify-content-between">
          Mobile Number
          <button type="button" class="close" data-dismiss="modal">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M15.5314 2.73138C16.1562 2.10653 16.1562 1.09347 15.5314 0.46863C14.9066 -0.15621 13.8935 -0.15621 13.2686 0.46863L8.00001 5.73727L2.73138 0.46863C2.10653 -0.15621 1.09347 -0.15621 0.46863 0.46863C-0.15621 1.09347 -0.15621 2.10653 0.46863 2.73138L5.73727 8.00001L0.46863 13.2686C-0.15621 13.8935 -0.15621 14.9066 0.46863 15.5314C1.09347 16.1562 2.10653 16.1562 2.73138 15.5314L8.00001 10.2627L13.2686 15.5314C13.8935 16.1562 14.9066 16.1562 15.5314 15.5314C16.1562 14.9066 16.1562 13.8935 15.5314 13.2686L10.2628 8.00001L15.5314 2.73138Z"
                    fill="#757575"/>
            </svg>
          </button>
        </div>

        <section class="modal-body">
          <div v-if="isLoading" class="loader d-flex justify-content-center">
            <div class="spinner"/>
          </div>

          <div v-show="!isLoading" ref="container" class="m-b-20"/>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import {auth} from 'firebaseui';
import firebase from 'firebase/app';
import parsePhoneNumber from 'libphonenumber-js';
import SnotifyWrapper from '../mixins/SnotifyWrapper';

export default {
  name: 'EditMobileModal',

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

  data: () => ({
    isLoading: false,
    error    : null,
    country  : null,
    mobile   : null
  }),

  computed: {
    ui() {
      return new auth.AuthUI(firebase.auth());
    }
  },

  async mounted() {
    await this.parseMobile();
    await this.loadCountry();
    this.initUi();
  },

  methods: {
    async parseMobile() {
      const profile = JSON.parse(this.profile);
      const {mobile} = profile;

      if (!mobile) {
        return;
      }

      try {
        const parser = await parsePhoneNumber(mobile);
        this.country = parser.country;
        this.mobile = parser.nationalNumber;
      } catch (error) {
      }
    },

    initUi() {
      const self = this;
      self.ui.start(self.$refs.container, {
        signInOptions: [
          {
            provider             : firebase.auth.PhoneAuthProvider.PROVIDER_ID,
            defaultNationalNumber: self.mobile,
            defaultCountry       : self.country,
            recaptchaParameters  : {
              type : 'image', // 'audio'
              size : 'normal', // 'invisible' or 'compact'
              badge: 'bottomleft' //' bottomright' or 'inline' applies to invisible.
            }
          }
        ],

        callbacks: {
          signInSuccessWithAuthResult: function (authResult) {
            self.mobile = authResult.user.phoneNumber;
            self.saveData();

            return false;
          }
        }
      });
    },

    async loadCountry() {
      if (this.country) {
        return;
      }

      this.isLoading = true;

      try {
        const {country} = await this.api.client.get('https://reader-dot-forwardchessbackend.uc.r.appspot.com/location');
        this.country = country;
      } catch (error) {

      }

      this.isLoading = false;
    },

    async saveData() {
      this.isLoading = true;

      try {
        await this.api.client.post(this.formAction, {mobile: this.mobile});
        this.showSuccessToast('Mobile number was saved.');
      } catch (error) {
        const {errors} = error;
        const message = (errors && errors.mobile)
            ? errors.mobile
            : 'There was a problem with saving your mobile number.';

        this.showErrorToast(message);
      }

      this.isLoading = false;
      await this.closeModal();
    },

    async closeModal() {
      $('#edit-mobile').modal('hide');
      this.ui.reset();
      this.initUi();
    }
  }
};
</script>
