<template>
    <div class="row">
        <income-graph :trans="trans" :data="data"></income-graph>
        <costs-graph :trans="trans" :data="data"></costs-graph>
        <div v-show="!loading" class="col-12">
            <div class="text-center">
                <div>{{ trans.available_balance }}: {{ data.total.income - data.total.cost }} SEK</div>
                <a class="btn btn-success mt-5" href="/economy">Read more about our economy here</a>
                <a class="d-block mt-1" href="/economy/transactions">View all of our transactions</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                data: {
                    total: {
                        income: null,
                        cost: null
                    },
                    categories: null,
                    transactions: null
                },
                loading: true,
                trans: economyTrans,
            }
        },
        components: {
            'costs-graph': require('./CostsGraph'),
            'income-graph': require('./IncomeGraph'),
        },
        mounted() {
            axios.get('/api-proxy', {
                params: {
                    url: '/api/v1/economy/transactions',
                }
            })
            .then(response => {
                this.loading = false;
                this.data = response.data;
            });
        },
    }
</script>
