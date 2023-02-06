<template>
    <div>
        <div class="price-filter">
            <h2>Price, $</h2>
            <div class="price-range-holder">
                <input type="text" class="price-slider" :value="localValue" name="priceRange">

                <span v-if="label" class="min-max">
                    Price: {{ label }}
                </span>
            </div>
        </div><!-- /.price-filter -->

        <div id="filter-button-container" class="filter-button d-flex d-none">
            <input type="number" placeholder="From" class="price-filter-from-to" v-model="fromValue">
            <input type="number" placeholder="To" class="price-filter-from-to" v-model="toValue">
            <button type="button" class="filter-button-apply w-100 m-l-10" @click="onButtonClick">OK</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            formSelector: {
                type    : String,
                required: true
            },

            value: {
                type    : String,
                required: true
            },

            label: {
                type   : String,
                default: ''
            }
        },

        data() {
            return {
                fromValue : null,
                toValue   : null,
                localValue: this.value
            };
        },

        methods: {
            onButtonClick() {
                if (this.fromValue && this.toValue && (this.fromValue < this.toValue)) {
                    this.localValue = `${this.fromValue},${this.toValue}`;
                }

                this.$nextTick(() => {
                    $(this.formSelector).submit();
                });
            }
        }
    }
</script>


