<template>
  <button type="submit"
          :class="classes"
          :disabled="disabled"
          :tabindex="tabindex">
    <i :class="['fa', icon]"></i>
    {{ label }}
  </button>
</template>

<script>
export default {
  name: 'SaveButton',

  props: {
    label: {
      type   : String,
      default: 'Save'
    },

    classes: {
      type   : String,
      default: 'btn btn-primary btn-lg btn-block'
    },

    icon: {
      type   : String,
      default: 'fa-save'
    },

    disabled: {
      type   : Boolean,
      default: false
    },

    tabindex: {
      type   : Number,
      default: 1
    }
  }
};
</script>