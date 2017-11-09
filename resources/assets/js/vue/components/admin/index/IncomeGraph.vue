<template>
    <div class="col-12 col-lg-6 mb-5">
        <div class="card">
            <div class="card-header">Income</div>
            <div class="card-body">
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                <h2 v-show="!loading" class="text-center">Income 2017 - Total {{ total }} SEK</h2>
                <div v-show="!loading" id="income-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                loading: true,
                incomeTransactions: null,
                total: null
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
                let data = this.formatData(response.data.transactions, response.data.categories);
                this.draw(data);
                this.loading = false;
            });
        },
        methods: {
            formatData(transactions, categories) {
                let filteredTransactions = _.filter(transactions, transaction => {
                    return transaction.amount > 0;
                })

                let incomes = _(filteredTransactions).groupBy(transaction => {
                    return transaction.category;
                }).map(function(category, categoryId) {
                    let sum = _.sumBy(category, transactions => {
                        return transactions.amount;
                    });

                    let categoryObject = _.find(categories, category => {
                        return category.id == categoryId;
                    });

                    let sumString = '\r' + sum + ' SEK';
                    let label = (categoryObject && categoryObject.label) ? categoryObject.label : 'Uncategorized';
                    label += sumString;

                    return [label, sum];
                })
                .value();

                let total = _.sumBy(filteredTransactions, transaction => {
                    return transaction.amount;
                });

                this.total = total;

                // Add headers
                incomes.unshift(['Category', 'Amount']);

                return incomes;
            },
            draw(dataArray) {
                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var dataTable = google.visualization.arrayToDataTable(dataArray);

                    var options = {
                        chartArea: {
                            left: 20,
                            top: 20,
                            width: '90%',
                            height: '90%',
                        },
                        tooltip: { trigger: 'selection' },
                        pieHole: 0.4,
                        legend: {
                            alignment: 'center',
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('income-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
