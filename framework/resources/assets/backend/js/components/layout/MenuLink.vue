<template>
    <a :href="item.url" @click.prevent="menuClicked(item)">
        <i :class="[item.icon]" aria-hidden="true"></i>
        <span>{{item.title}}</span>
    </a>
</template>

<script>
    import * as paths from '../../router/paths';
    import * as names from '../../router/names';

    export default {
        name: 'MenuLink',

        props: {
            item: {
                type    : Object,
                required: true
            }
        },

        methods: {
            menuClicked(item) {
                this.$emit('clicked', item);
            }
        }
    };
</script>
