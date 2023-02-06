<template>
    <div>
        payment info
    </div>
</template>

<script>
    import TabMixin from './TabMixin';

    export default {
        mixins: [
            TabMixin
        ],

        methods: {
            getResourceUrl(id) {
                return `orders/${id}/payment`;
            }
        }
    };
</script>
