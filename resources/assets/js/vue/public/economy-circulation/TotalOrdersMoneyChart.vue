<template>
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6 mb-5">
            <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
            <div v-show="!loading" class="metrics">
                <div class="metric">
                    <h3 class="value">{{ data.orders }}</h3>
                    <div class="label">Orders</div>
                </div>
                <div class="metric">
                    <h3 class="value">{{ parseInt(data.sum).toLocaleString('sv') }}</h3>
                    <div class="label">Money Circulated Locally (sek)</div>
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
        font-size: 40px;
        font-weight: 700;
        padding: 10px 40px 0;
        margin-bottom: -5px;
        color: #999;
    }
    .metric .label {
        font-family: 'montserrat';
        text-transform: uppercase;
        font-weight: bold;
        color: #999;
    }
</style>

<script>
    export default {
        data: function() {
            return {
                data: {
                    orders: null
                },
                loading: true,
            }
        },
        mounted() {
            axios.get('/api-proxy', {
                params: {
                    url: '/api/v1/orders',
                }
            })
            .then(response => {
                this.loading = false;
                this.data = this.formatData(response.data)
            });
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
