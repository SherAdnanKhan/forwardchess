<template>
    <div>
        <div v-if="error" class="well danger">
            <h4 class="invalid-feedback" v-show="error.message">{{error.message}}</h4>

            <ul v-if="list.length">
                <li v-for="fieldError in list">{{fieldError}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: 'ApiError',

        props: ['error'],

        data() {
            return {
                list: []
            };
        },

        watch: {
            'error': {
                immediate: true,
                handler  : 'setFieldsErrors'
            }
        },

        methods: {
            setFieldsErrors() {
                this.list = [];

                if (this.error && this.error.hasOwnProperty('errors')) {
                    _.each(this.error.errors, errors => {
                        _.each(errors, error => {
                            this.list.push(error);
                        });
                    });
                }
            }
        }
    };
</script>
