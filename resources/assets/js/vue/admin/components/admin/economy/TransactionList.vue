<template>
    <div class="card mb-5">
        <div class="card-header">Transactions</div>
        <div class="card-body">

            <i v-show="fetching" class="fa fa-spinner fa-spin"></i>

            <table v-show="!fetching" class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Ref</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>
                            <select class="form-control" v-model="filteredCategory" @change="filterCategory">
                                <option value="null">All categories</option>
                                <option v-for="category in categories" :value="category.id">{{ category.type_label }}</option>
                                <option value="-1">Uncategorized</option>
                            </select>
                        </th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <transaction-item
                        v-for="transaction in filteredTransactions"
                        :transaction="transaction"
                        :categories="categories"
                        :key="transaction.hash">
                    </transaction-item>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
    th {
        vertical-align: middle !important;
    }
    select.form-control:not([size]):not([multiple]) {
        height: 100% !important;
    }
</style>

<script>
    export default {
        props: ['transactions', 'categories', 'fetching'],
        components: {
            'transaction-item': require('./TransactionItem'),
        },
        data: function() {
            return {
                filteredCategory: null,
                filteredTransactions: null,
            };
        },
        watch: {
            transactions(transactions) {
                this.filteredTransactions = transactions;
            }
        },
        methods: {
            filterCategory(event) {
                let filteredCategory = event.target.value;

                if (filteredCategory == 'null') {
                    this.filteredTransactions = this.transactions;
                } else if (filteredCategory == '-1') {
                    this.filteredTransactions = _.filter(this.transactions, transaction => {
                        return transaction.category == null;
                    });
                } else {
                    this.filteredTransactions = _.filter(this.transactions, transaction => {
                        return transaction.category == filteredCategory;
                    });
                }
            }
        }
    }
</script>
