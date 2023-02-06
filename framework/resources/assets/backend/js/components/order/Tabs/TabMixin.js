import EditForm from "../../mixins/EditForm";

export default {
    mixins: [
        EditForm
    ],

    props: {
        orderId: {
            type    : String,
            required: true
        },

        visible: {
            type   : Boolean,
            default: false
        }
    },

    watch: {
        'visible': {
            immediate: true,
            handler  : function (isVisible) {
                if (this.isEmpty && isVisible) {
                    this.getModel(this.orderId);
                }
            }
        },
    }
}
