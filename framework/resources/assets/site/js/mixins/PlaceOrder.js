export default {
    inject: [
        'api'
    ],

    props: {
        formAction: {
            type    : String,
            required: true
        },

        billing: {
            type   : String,
            default: JSON.stringify({})
        }
    },

    data() {
        const billing  = JSON.parse(this.billing),
              getValue = (property) => {
                  return billing.hasOwnProperty(property) ? billing[property] : '';
              };

        return {
            isLoading: false,
            error    : null,
            country  : getValue('country'),
            email    : getValue('email'),
            form     : {
                firstName: getValue('firstName'),
                lastName : getValue('lastName')
            }
        }
    },

    methods: {
        scrollToTop() {
            $('html, body').animate({scrollTop: 0}, 'slow');
        },

        async placeOrder() {
            const success = await this.$validator.validate();
            this.scrollToTop();

            if (!success) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await this.api.client.post(this.formAction, this.form);
                if (response.hasOwnProperty('url')) {
                    window.top.location.replace(response.url);
                }
                return;
            } catch (error) {
                this.$snotify.error('There was a problem with placing the order!');
                this.error = error;
            }

            this.isLoading = false;
        }
    }
}