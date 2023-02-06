<template>
    <span class="name">{{ user.firstName }} {{ user.lastName }}</span>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: 'UserName',

        computed: {
            ...mapGetters([
                'user'
            ])
        }
    };
</script>
