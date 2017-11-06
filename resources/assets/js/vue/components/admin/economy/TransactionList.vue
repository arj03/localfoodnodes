<template>
    <div class="card mb-5">
        <div class="card-header">Transactions</div>
        <div class="card-body">

            <i v-show="loading" class="fa fa-spinner fa-spin"></i>

            <table v-show="!loading" class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Ref</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th colspan="2">Category</th>
                    </tr>
                </thead>

                <tbody>
                    <transaction-item v-for="transaction in transactions" v-bind:transaction="transaction" v-bind:categories="categories" :key="transaction.hash"></transaction-item>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'transaction-item': require('./TransactionItem'),
        },
        data: function() {
            return {
                transactions: null,
                categories: null,
                loading: true,
            }
        },
        mounted() {
            axios.get('/admin/token')
            .then(response => {
                return axios.get('/api/v1/economy/transactions', {
                    headers: {
                        'Authorization': 'Bearer ' + response.data
                    }
                });
            })
            .then(response => {
                this.transactions = response.data.transactions;
                this.categories = response.data.categories;
                this.loading = false;
            });
        }
    }
</script>
