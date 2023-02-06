export default {
    methods: {
        showToast(type, message) {
            const errorIcon = `<svg class="m-r-10" width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5314 2.73138C16.1562 2.10653 16.1562 1.09347 15.5314 0.46863C14.9066 -0.15621 13.8935 -0.15621 13.2686 0.46863L8.00001 5.73727L2.73138 0.46863C2.10653 -0.15621 1.09347 -0.15621 0.46863 0.46863C-0.15621 1.09347 -0.15621 2.10653 0.46863 2.73138L5.73727 8.00001L0.46863 13.2686C-0.15621 13.8935 -0.15621 14.9066 0.46863 15.5314C1.09347 16.1562 2.10653 16.1562 2.73138 15.5314L8.00001 10.2627L13.2686 15.5314C13.8935 16.1562 14.9066 16.1562 15.5314 15.5314C16.1562 14.9066 16.1562 13.8935 15.5314 13.2686L10.2628 8.00001L15.5314 2.73138Z" fill="white"/> </svg>`
            const successIcon = `<svg class="m-r-10" width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.913 0L16 1.94118L6.27478 11L0 5.17647L2.08696 3.23529L6.27478 7.11765L13.913 0Z" fill="white"/> </svg>`

            this.$snotify[type]('', {
                showProgressBar: false,
                html           : `<div class="d-flex align-items-center">
                    ${type === 'success' ? successIcon : errorIcon}
                    <span>${message}</span>
                </div>`
            });
        },

        showSuccessToast(message) {
            this.showToast('success', message);
        },

        showErrorToast(message) {
            this.showToast('error', message);
        }
    }
}