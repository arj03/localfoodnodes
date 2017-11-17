<template>
    <div class="card mb-5">
        <div class="card-header">Users</div>
        <div class="card-body">
            <i v-show="loading" class="fa fa-spinner fa-spin"></i>
            <table v-show="!loading" class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <user-item v-for="user in users" v-bind:user="user" :key="user.id"></user-item>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['users'],
        components: {
            'user-item': require('./UserItem')
        },
        data: function() {
            return {
                loading: true
            }
        },
        watch: {
            users: function(users) {
                if (users) {
                    this.loading = false;
                }
            }
        }
    }
</script>
