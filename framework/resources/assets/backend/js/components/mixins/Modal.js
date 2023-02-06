import EditForm from './EditForm';
import SimpleModal from './SimpleModal';

export default {
  mixins: [
    SimpleModal,
    EditForm
  ],

  props: {
    modelId: {
      type   : [Number, String],
      default: null
    },

    resourceUrl: {
      type    : String,
      required: true
    }
  },

  watch: {
    'state': {
      immediate: true,
      handler  : function (state) {
        if (state) {
          this.loadModel();
        }
      }
    }
  },

  methods: {
    async loadModel() {
      if (this.modelId === 'add') {
        this.createModel();
      } else {
        await this.getModel(this.modelId);
      }
    },

    afterSave() {
      this.$emit('saved');
    },

    getResourceUrl(id = null) {
      return id ? `${this.resourceUrl}/${id}` : this.resourceUrl;
    }
  }
};