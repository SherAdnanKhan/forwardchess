<template>
  <div class="tags-input">
    <Multiselect
        v-model="selected"
        id="ajax"
        open-direction="bottom"
        :internal-search="false"
        :options-limit="300"
        :limit="30"
        :loading="isLoading"
        :options="options"
        :custom-label="getLabel"
        :tabindex="tabindex"
        :name="name"
        :multiple="multiple"
        openDirection="below"
        select-label=""
        :show-no-results="false"
        :placeholder="placeholder"
        :searchable="true"
        :close-on-select="false"
        :clear-on-select="false"
        :hide-selected="true"
        :preserve-search="false"
        @input="onChange"
        :track-by="valueProperty"
        :preselect-first="false"
        @search-change="debounceAsyncFind"
    >
      <span slot="noResult">{{ noResultsText }}</span>
    </Multiselect>
  </div>
</template>

<script>
import _ from 'lodash';
import Multiselect from 'vue-multiselect';

export default {
  name: 'CustomSearch',

  inject: ['api'],

  components: {
    Multiselect
  },

  props: {
    url: {
      type    : String,
      required: true
    },

    name: {
      type    : String,
      required: true
    },

    disabled: {
      type   : Boolean,
      default: false
    },

    multiple: {
      type   : Boolean,
      default: false
    },

    tabindex: {
      type   : Number,
      default: 0
    },

    labelProperty: {
      type   : String,
      default: 'name'
    },

    valueProperty: {
      type   : String,
      default: 'id'
    },

    selectedValue: {
      type: Array
    },

    value: {
      required: true
    },

    minNrOfItems: {
      type   : Number,
      default: 5
    },

    placeholder: {
      type   : String,
      default: 'Search'
    },

    customLabel: {
      type   : Function,
      default: null
    }
  },

  watch: {
      // 'value'(newVal) {
      //   if(newVal.length > 0 && newVal[0].id !== undefined) {
      //     this.selected = this.populateSelected(newVal, this.options);
      //     this.onChange();
      //   }
      // }
  },

  computed: {
    isSearchable() {
      return (this.options.length > this.minNrOfItems);
    },

    isDisabled() {
      return this.disabled || (this.options.length <= 1);
    },

    debounceAsyncFind() {
      return _.debounce(this.asyncFind, 500);
    }
  },

  data() {
    return {
      selected     : null,
      noResultsText: 'No results matched',
      options      : this.selectedValue ? this.selectedValue : [],
      isLoading    : false,
      limits       : 50
    };
  },

  created() {
    this.selected = this.populateSelected(this.selectedValue, this.options);
    this.onChange();
  },

  methods: {
    populateSelected(selections, options) {
      const selected = [];
      for (const opt of options) {
        if (_.isArray(selections)) {
          if (selections.indexOf(opt[this.valueProperty]) === -1) {
            selected.push(opt);
          }
        } else if (selections === opt[this.valueProperty]) {
          selected.push(opt);
        }
      }
      return selected;
    },

    onChange() {
      if (this.multiple) {
        this.$emit('input', this.selected ? this.selected.map(x => x[this.valueProperty]) : []);
      } else {
        this.$emit('input', this.selected ? this.selected[this.valueProperty] : '');
      }
    },

    async asyncFind(query) {
      this.isLoading = true;

      try {
        const url = this.url + (this.url.includes('?') ? '&' : '?') + `query=${query}&limits=${this.limits}`;
        this.options = await this.api.client.get(url);
      } catch (e) {
        console.log(e);
      }

      this.isLoading = false;
    },

    getLabel(item) {
      return this.customLabel ? this.customLabel(item) : item[this.labelProperty];
    }
  }
};
</script>
