<template>
    <tr v-show="show" class="table-row">
        <td class="col-label">
           {{label}}
        </td>
        <td class="col-value">
           {{valueFormatted}}
        </td>
    </tr>
</template>

<script>
    export default {
        name: 'TableRow',

        props: {
            label: {
                type    : String,
                required: true
            },

            value: {
                default: ''
            },

            type: {
                type   : String,
                default: ''
            }
        },

        computed: {
            show() {
                return this.value;
            },

            valueFormatted() {
                switch (this.type) {
                    case 'amount':
                        return `$${this.value}`;
                    case 'percent':
                        return `${this.value}%`;
                    default:
                        return this.value;
                }
            }
        }
    }
</script>