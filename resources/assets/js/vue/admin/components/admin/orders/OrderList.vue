<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Orders</div>
                    <div class="card-body">
                        <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                        <table v-show="!loading" class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Node</th>
                                    <th>Product</th>
                                    <th>Date</th>
                                    <th>Sum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <order-item v-for="order in orders" v-bind:order="order" :key="order.id"></order-item>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['orders'],
        components: {
            'order-item': require('./OrderItem'),
        },
        data: function() {
            return {
                loading: true,
            }
        },
        watch: {
            orders: function(orders) {
                if (orders) {
                    this.loading = false;
                }
            }
        }
    }
</script>
