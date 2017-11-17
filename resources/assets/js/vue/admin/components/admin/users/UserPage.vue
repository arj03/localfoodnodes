<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <signup-graph :data="data"></signup-graph>
                <user-list :users="data.users"></user-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'signup-graph': require('./SignupGraph'),
            'user-list': require('./UserList'),
        },
        data: function() {
            return {
                data: {
                    users: null,
                    nodes: null,
                    producers: null
                },
            }
        },
        mounted() {
            axios.all([
                this.getUsers(),
                this.getNodes(),
                this.getProducers()
            ])
            .then(axios.spread((users, nodes, producers) => {
                this.data = {
                    users: users.data,
                    nodes: nodes.data,
                    producers: producers.data,
                };
            }));
        },
        methods: {
            getUsers() {
                return axios.get('/admin/api-proxy', {
                    params: {
                        url: '/api/v1/users'
                    }
                })
                .catch(error => {
                    console.error('Error in getUsers', error);
                });
            },
            getNodes() {
                return axios.get('/admin/api-proxy', {
                    params: {
                        url: '/api/v1/nodes'
                    }
                })
                .catch(error => {
                    console.error('Error in getNodes', error);
                });
            },
            getProducers() {
                return axios.get('/admin/api-proxy', {
                    params: {
                        url: '/api/v1/producers'
                    }
                })
                .catch(error => {
                    console.error('Error in getProducers', error);
                });
            },
        }
    }
</script>
