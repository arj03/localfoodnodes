<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <user-signup-graph v-bind:users="users"></user-signup-graph>
                <user-list v-bind:users="users"></user-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'user-signup-graph': require('./UserSignupGraph'),
            'user-list': require('./UserList'),
        },
        data: function() {
            return {
                users: null,
            }
        },
        mounted() {
            axios.get('/admin/token')
            .then(response => {
                return axios.get('/api/v1/users', {
                    headers: {
                        'Authorization': 'Bearer ' + response.data
                    }
                });
            })
            .then(response => {
                this.users = response.data;
            });
        }
    }
</script>
