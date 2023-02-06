import VeeValidate from 'vee-validate';
import Vue from 'vue';
import parsePhoneNumber from 'libphonenumber-js';

VeeValidate.Validator.extend('mobile', {
  getMessage(field) {
    return `The ${field} must a valid phone number`;
  },

  async validate(value) {
    const phoneNumber = await parsePhoneNumber(value);

    return (phoneNumber !== undefined) && phoneNumber.isValid();
  }
});

Vue.use(VeeValidate, {
  events       : 'blur',
  fieldsBagName: 'veeFields'
});
