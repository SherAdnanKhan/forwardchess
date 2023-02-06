<template>
    <header class="page-header">
        <h2>{{ title }}</h2>

        <div class="right-wrapper pull-right" v-if="breadcrumbs">
            <ol class="breadcrumbs">
                <li v-for="item in breadcrumbs">
                    <router-link v-if="item.url" :to="item.url">
                        <i v-if="item.icon" :class="[item.icon]" aria-hidden="true"></i>
                        <span v-if="item.name">{{item.name}}</span>
                    </router-link>
                    <span v-else> {{item.name}}</span>
                </li>
            </ol>
        </div>
    </header>
</template>

<script>
    export default {
        name: 'PageHeader',

        props: {
            title : {
                type    : String,
                required: true
            },
            breadcrumbs: {
                type    : Array
            }
        }
    };
</script>
