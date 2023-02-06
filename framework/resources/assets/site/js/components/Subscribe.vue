<template>
    <form class="subscribe-form" role="form" @submit.prevent="subscribe" autocomplete="off">
        <div class="title">
            stay tuned!
        </div>
        <div class="primary-description text-center m-b-50">
            Subscribe to our newsletter to hear about new features, latest releases, and special offers
        </div>
        <div class="wrapper-subscribe d-flex justify-content-center align-items-center">
            <div class="wrapper-input d-flex justify-content-center">
                <input name="email"
                       :class="{'is-invalid': errors.has('email'), 'loading': isLoading}"
                       maxlength="255"
                       v-model="email"
                       v-validate="'required|email|max:255'"
                       :disabled="isLoading"
                       placeholder="Email address">
            </div>
            <button class="button-subscribe" type="submit" :disabled="isLoading">Subscribe</button>
        </div>

        <span  class="error d-flex justify-content-center w-100">
            <div v-show="errors.has('email')" class="wrapper-error-block">
                <strong>The email field must be a valid email!</strong>
            </div>
        </span>

        <api-error :error="error"></api-error>
    </form>
</template>

<script>
    import SnotifyWrapper from '../mixins/SnotifyWrapper.js';

    export default {
        inject: [
            'api'
        ],

        data() {
            return {
                isLoading: false,
                error    : null,
                email    : ''
            };
        },

        mixins: [
            SnotifyWrapper,
        ],

        methods: {
            async subscribe() {
                if (this.isLoading) {
                    return;
                }

                const success = await this.$validator.validate();
                if (!success) {
                    return;
                }

                this.error = null;
                this.isLoading = true;

                try {
                    await this.api.client.post('subscribe', {email: this.email});
                    this.showSuccessToast('You successfully subscribed to our newsletter!');
                    this.email = '';
                } catch (error) {
                    this.error = error;
                }

                this.isLoading = false;
            }
        }
    }
</script>
