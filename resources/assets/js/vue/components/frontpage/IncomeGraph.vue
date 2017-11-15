<template>
    <div>
        <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
        <h3 v-show="!loading" class="text-center">{{ trans.income }} 2017 - {{ trans.total }} {{ total }} SEK</h3>
        <div v-show="!loading" id="income-chart" class="chart" style="height: 300px;"></div>
    </div>
</template>

<script>
    export default {
        props: ['trans'],
        data: function() {
            return {
                loading: true,
                incomeTransactions: null,
                total: null
            }
        },
        mounted() {
            axios.get('/api-proxy', {
                params: {
                    url: '/api/v1/economy/transactions',
                }
            })
            .then(response => {
                let data = this.formatData(response.data.transactions, response.data.categories);
                this.draw(data);
                this.loading = false;
            });
        },
        methods: {
            formatData(transactions, categories) {
                let trans = this.trans;

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
                    let label = (categoryObject && categoryObject.label) ? trans['category_' + categoryObject.id] : trans.uncategorized;
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
                            position: 'left',
                            textStyle: {
                                float: 'right'
                            }
                        },
                        slices: {
                            0: { color: '#f2e3b4' },
                            1: { color: '#e7d7a6' },
                            2: { color: '#d2c08b' },
                            3: { color: '#c8b67f' },
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('income-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
