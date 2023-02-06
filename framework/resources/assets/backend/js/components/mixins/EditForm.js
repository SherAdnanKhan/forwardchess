import EditPage from '../templates/EditPage.vue';
import ApiError from '../../../../common/components/ApiError.vue';
import InputField from '../layout/InputField';
import TextareaField from '../layout/TextareaField';
import CustomSelect from '../../../../common/components/CustomSelect';
import CustomSearch from '../../../../common/components/CustomSearch';
import SaveButton from '../layout/SaveButton';

export default {
  inject: ['api'],

  components: {
    EditPage,
    ApiError,
    InputField,
    TextareaField,
    CustomSelect,
    CustomSearch,
    SaveButton
  },

  computed: {
    isEmpty() {
      return (this.model === null);
    }
  },

  data() {
    return {
      isLoading: true,
      error    : null,
      model    : null
    };
  },

  watch: {
    error(value) {
      if (value) {
        window.scrollTo(0, 0);
      }
    }
  },

  methods: {
    createModel() {
      this.setModel({});
      this.isLoading = false;
    },

    setModel(model) {
      this.model = model;
    },

    async getModel(id) {
      this.isLoading = true;

      let model;
      try {
        model = await this.api.client.get(this.getResourceUrl(id));
      } catch (e) {
        model = null;
      }

      this.setModel(model);

      this.isLoading = false;
    },

    transformModel() {
      return this.model;
    },

    async save() {
      const isValid = await this.$validator.validateAll();
      if (!isValid) {
        return;
      }

      this.isLoading = true;
      this.error = null;

      try {
        let model = this.transformModel();

        if (this.model.id) {
          model = await this.api.client.put(this.getResourceUrl(this.model.id), model);
        } else {
          model = await this.api.client.post(this.getResourceUrl(), model);
        }

        this.setModel(model);
        this.afterSave(this.model);
      } catch (error) {
        this.error = error;
      }

      this.isLoading = false;
    },

    getResourceUrl() {
      return null;
    },

    afterSave(model) {

    }
  }
};