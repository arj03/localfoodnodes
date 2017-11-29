<template>
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 mb-5">
            <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
            <div v-show="!loading" class="row metrics">
                <div class="metric col-12 col-sm-6">
                    <i class="fa fa-shopping-cart"></i>
                    <div class="metric-inner">
                        <div class="value">{{ data.orders }}</div>
                        <div class="label">{{ this.trans.orders }}</div>
                    </div>
                </div>
                <div class="metric col-12 col-sm-6">
                    <i class="fa fa-refresh"></i>
                    <div class="metric-inner">
                        <div class="value">{{ parseInt(data.sum).toLocaleString('sv') }}</div>
                        <div class="label">{{ this.trans.money_circulated }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .metric .fa,
    .metric .value {
        color: #dec285;
    }
</style>

<script>
    export default {
        props: ['translations'],
        data: function() {
            return {
                data: {
                    orders: null
                },
                loading: true,
                trans: {}
            }
        },
        mounted() {
            this.trans = JSON.parse(this.translations);
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
