<template>
    <div class="col-12 mb-5">
        <div class="card">
            <div class="card-header">Orders by node</div>
            <div class="card-body">
                <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
                <div class="metrics" v-show="!loading">
                    <div class="metric">
                        <h3 class="value">{{ data.orders }}</h3>
                        <div class="label">Orders</div>
                    </div>
                    <div class="metric">
                        <h3 class="value">{{ parseInt(data.sum).toLocaleString('sv') }}</h3>
                        <div class="label">Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .metrics {
        display: flex;
        justify-content: space-around;
    }
    .metric {
        text-align: center;
    }
    .metric .value {
        font-family: 'montserrat';
        font-size: 1.75rem;
        font-weight: 500;
        border-bottom: 5px solid #dec286;
        padding: 10px 40px 0;
        margin-bottom: 5px;
    }
</style>

<script>
    export default {
        props: ['orders'],
        data: function() {
            return {
                data: null,
                loading: true,
                incomeTransactions: null,
            }
        },
        watch: {
            orders: function(orders) {
                this.data = this.formatData(orders);
                this.loading = false;
            }
        },
        methods: {
            formatData(orders) {
                let totalSum = _.sumBy(orders, order => {
                    let price = order.order_item_relationship[0].product.price;
                    let quantity = order.quantity;

                    return price * quantity;
                });

                return {
                    orders: orders.length,
                    sum: totalSum,
                };
            },
        }
    }
</script>
