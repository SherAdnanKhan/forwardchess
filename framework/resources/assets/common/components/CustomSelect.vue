<template>
    <div class="tags-input">
        <Multiselect
                v-model="selected"
                :options="options"
                :disabled="isDisabled"
                :tabindex="tabindex"
                :name="name"
                :multiple="multiple"
                openDirection="below"
                select-label=""
                :show-no-results="false"
                :placeholder="placeholder"
                :searchable="isSearchable"
                :close-on-select="true"
                :clear-on-select="true"
                :hide-selected="true"
                :preserve-search="false"
                @input="onChange"
                :label="labelProperty"
                :track-by="labelProperty"
                :preselect-first="false"
        >
            <span slot="noResult">{{noResultsText}}</span>
        </Multiselect>
    </div>
</template>
<script>
    import _ from 'lodash';
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'CustomSelect',

        components: {
            Multiselect
        },

        props: {
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

            options: {
                type    : Array,
                required: true
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
        },

        watch: {
            'value'(newVal) {
                this.selected = this.populateSelected(newVal, this.options);
            }
        },

        computed: {
            isSearchable() {
                return (this.options.length > this.minNrOfItems);
            },

            isDisabled() {
                return this.disabled || (this.options.length <= 1)
            }
        },

        data() {
            return {
                selected     : null,
                noResultsText: 'No results matched'
            };
        },

        created() {
            this.selected = this.populateSelected(this.value, this.options);
        },

        methods: {
            populateSelected(selections, options) {
                const selected = [];
                for (const opt of options) {
                    if (_.isArray(selections)) {
                        if (selections.indexOf(opt[this.valueProperty]) !== -1) {
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
            }
        }
    };
</script>
