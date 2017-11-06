<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <orders-by-node-graph :orders="orders"></orders-by-node-graph>
                <order-list :orders="orders"></order-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'order-list': require('./OrderList'),
            'orders-by-node-graph': require('./OrdersByNodeGraph'),
        },
        data: function() {
            return {
                orders: null
            }
        },
        mounted() {
            axios.get('/admin/token')
            .then(response => {
                return axios.get('/api/v1/orders', {
                    headers: {
                        'Authorization': 'Bearer ' + response.data
                    }
                });
            })
            .then(response => {
                this.orders = response.data;
            });
        }
    }
</script>
