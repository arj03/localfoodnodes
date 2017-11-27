<template>
    <div class="row">
        <income-graph :trans="trans" :data="data"></income-graph>
        <costs-graph :trans="trans" :data="data"></costs-graph>
        <div v-show="!loading" class="col-12">
            <div class="text-center mb-5">
                <h3>{{ (data.total.income - data.total.cost).toLocaleString('sv') }} SEK</h3>
                <div>{{ trans.available_balance }}</div>
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
