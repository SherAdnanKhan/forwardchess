<template>
  <div class="form-group" :class="{'has-errors': errorMsg}">
    <label>{{ label }}</label>
    <textarea
        class="form-control m-wrap span12"
        :name="name"
        :placeholder="label"
        :readonly="readonly"
        :disabled="disabled"
        :tabindex="tabindex"
        :value="value"
        @input="input"
        @blur="blur"
    />
    <span v-show="errorMsg" class="error-msg">{{ errorMsg }}</span>
  </div>
</template>

<script>
export default {
  name: 'TextareaField',

  props: {
    column: {
      type   : String,
      default: ''
    },

    label: {
      type    : String,
      required: true
    },

    name: {
      type   : String,
      default: ''
    },

    value: {
      default: ''
    },

    readonly: {
      type   : Boolean,
      default: false
    },

    disabled: {
      type   : Boolean,
      default: false
    },

    tabindex: {
      type   : Number,
      default: 1
    },

    errorMsg: {
      type   : String,
      default: ''
    }
  },

  methods: {
    input(event) {
      this.$emit('input', event.target.value);
    },

    blur(event) {
      this.$emit('blur', event.target.value);
    }
  }
};
</script>