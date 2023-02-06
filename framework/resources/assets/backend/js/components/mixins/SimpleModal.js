export default {
    props: {
        state  : {
            type   : Boolean,
            default: false
        }
    },

    watch: {
        'state'(state) {
            $(this.$refs.modal).modal(state ? 'show' : 'hide');
        }
    },

    mounted() {
        this.initModal();
    },

    beforeDestroy() {
        $(this.$refs.modal).modal('hide');
        this.onClose();
    },

    methods: {
        initModal() {
            $(this.$refs.modal)
                .modal({
                    keyboard: false,
                    show    : this.state,
                    backdrop: 'static'
                })
                .on('hidden.bs.modal', () => this.onClose());
        },

        onClose() {
            this.$emit('close');
        }
    }
};