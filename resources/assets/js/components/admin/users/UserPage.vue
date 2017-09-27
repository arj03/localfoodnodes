<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <user-signups v-if="ready" :users="users"></user-signups>
                <user-list v-if="ready" :users="users"></user-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'user-signups': require('./UserSignups'),
            'user-list': require('./UserList'),
        },
        data: function() {
            return {
                users: [],
                ready: false
            }
        },
        created() {
            axios.get('/api/users')
            .then(response => {
                this.users = response.data;
                this.ready = true;
            });
        }
    }
</script>
