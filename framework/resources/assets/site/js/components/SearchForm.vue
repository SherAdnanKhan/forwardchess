<template>
  <form :action="action" method="get" id="headerSearchForm" autocomplete="off" ref="form" class="search-form" @keyup.enter="submit">
    <div class="control-group d-flex justify-content-between">
      <div class="search-container">
        <suggestions
            name="search"
            :options="options"
            v-model="query"
            :onInputChange="onInputChange"
            :onItemSelected="onSearchItemSelected"
        >
          <div slot="item" slot-scope="props" class="single-item d-flex flex-column">
            <span class="title secondary-title" v-html="highlight(props.item.title)"></span>
            <span class="author" v-html="highlight(`${props.item.author}`)"></span>
          </div>
        </suggestions>
      </div>

      <button type="button" class="search-button" @click="submit"></button>

      <div class="clearfix"></div>
    </div>
  </form>
</template>

<script>
import suggestions from 'v-suggestions';

export default {
  inject: [
    'api'
  ],

  components: {
    suggestions
  },

  props: {
    action: {
      type   : String,
      default: ''
    },

    initialValue: {
      type   : String,
      default: ''
    },

    environment: {
      type   : String,
      default: ''
    }
  },

  data() {
    return {
      query  : this.initialValue,
      options: {
        debounce   : 200,
        inputClass : 'search-field',
        placeholder: 'Title, author...'
      }
    };
  },

  methods: {
    onInputChange(query) {
      if (!query || query.trim().length < 3) {
        return null;
      }

      return this.api.client.get(`${window.apiBaseURL}/products/search`, {params: {query}});
    },

    onSearchItemSelected(value) {
      this.query = value.title;

      this.throwEvent();

      this.$nextTick(() => {
        $(this.$refs.form).submit();
      });
    },

    highlight(text) {
      const re = new RegExp(this.query, 'ig');

      return text.replace(re, function (theWord) {
        return `<span class="highlight">${theWord}</span>`;
      });
    },

    submit() {
      this.throwEvent();
      $(this.$refs.form).submit();
    },

    throwEvent() {
      if (this.environment === 'production') {
        ga('send', 'event', 'Search', 'Keyword', this.query);
      }
    }
  }
};
</script>
