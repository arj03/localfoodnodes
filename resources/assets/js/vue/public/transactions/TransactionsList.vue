<template>
    <div>
        <div v-show="!loading" class="filters mb-5">
            <button class="btn btn-secondary" v-on:click="filterTransactions(-1, $event)">{{ trans.all }}</button><button class="btn btn-secondary" v-on:click="filterTransactions(categories.income, $event)">{{ trans.all_incomes }}</button><button class="btn btn-secondary" v-on:click="filterTransactions(categories.cost, $event)">{{ trans.all_costs}}</button><button class="btn btn-secondary" v-for="category in this.categories.all" v-on:click="(filterTransactions(category.id, $event))">{{ trans['category_' + category.id] }}</button>
        </div>

        <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
        <div class="table-responsive">
            <table v-show="!loading" class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ trans.date }}</th>
                        <th>{{ trans.ref }}</th>
                        <th>{{ trans.description }}</th>
                        <th>{{ trans.amount }}</th>
                        <th>{{ trans.category }}</th>
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
    </div>
</template>

<style scoped>
    .table th {
        font-size: 14px;
    }
    .btn {
        text-transform: capitalize;
        font-size: 12px;
        margin: 5px;
        padding: 5px 10px;
    }
</style>

<script>
    export default {
        props: ['translations'],
        components: {
            'transaction-item': require('./TransactionItem'),
        },
        data: function() {
            return {
                trans: {},
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
            this.trans = JSON.parse(this.translations);

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
