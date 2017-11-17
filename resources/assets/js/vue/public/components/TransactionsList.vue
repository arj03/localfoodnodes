<template>
    <div>
        <div v-show="!loading" class="filters mb-5">
            <button class="btn btn-secondary" v-on:click="filterTransactions(-1, $event)">All</button><button class="btn btn-secondary" v-on:click="filterTransactions(categories.income, $event)">All incomes</button><button class="btn btn-secondary" v-on:click="filterTransactions(categories.cost, $event)">All costs</button><button class="btn btn-secondary" v-for="category in this.categories.all" v-on:click="(filterTransactions(category.id, $event))">{{ category.label }}</button>
        </div>

        <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
        <table v-show="!loading" class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Ref</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Category</th>
                </tr>
            </thead>

            <tbody>
                <transaction-item
                    v-for="transaction in filteredTransactions"
                    :transaction="transaction"
                    :categories="categories.all"
                    :key="transaction.hash">
                </transaction-item>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
    .btn {
        text-transform: capitalize;
        font-size: 12px;
        margin: 1px;
        padding: 5px;
    }
</style>

<script>
    export default {
        components: {
            'transaction-item': require('./TransactionItem'),
        },
        data: function() {
            return {
                loading: true,
                transactions: null,
                filteredTransactions: null,
                categories: {
                    all: null,
                    income: null,
                    cost: null
                },
            };
        },
        mounted() {
            axios.get('/api-proxy', {
                params: {
                    url: '/api/v1/economy/transactions'
                }
            })
            .then(response => {
                this.transactions = response.data.transactions;
                this.filteredTransactions = response.data.transactions;
                this.categories = response.data.categories;
                this.loading = false;
            });
        },
        methods: {
            getFilter() {
                let filters = this.categories.all.concat()
            },
            filterTransactions(filteredCategory, event) {
                if (!_.isArray(filteredCategory)) {
                    filteredCategory = [filteredCategory];
                }

                if (_.includes(filteredCategory, -1)) {
                    this.filteredTransactions = this.transactions;
                } else {
                    this.filteredTransactions = _.filter(this.transactions, transaction => {
                        return _.includes(filteredCategory, parseInt(transaction.category));
                    });
                }
            }
        }
    }
</script>
