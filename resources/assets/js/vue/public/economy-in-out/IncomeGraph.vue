<template>
    <div class="col-12 col-lg-6 justify-content-center">
        <h3 v-show="!loading" class="text-center">{{ parseInt(total).toLocaleString('sv') }} SEK</h3>
        <div v-show="!loading" class="text-center">{{ trans.income }} 2017</div>
        <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
        <div v-show="!loading" id="income-chart" class="chart" style="height: 300px; width: 100%;"></div>
    </div>
</template>

<script>
    export default {
        props: ['trans', 'data'],
        data: function() {
            return {
                loading: true,
                incomeTransactions: null,
                total: null
            }
        },
        watch: {
            data(data) {
                let formattedData = this.formatData(data.transactions, data.categories.all);
                this.draw(formattedData);
                this.loading = false;
            }
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
                google.charts.load('current', {packages:['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var dataTable = google.visualization.arrayToDataTable(dataArray);

                    var options = {
                        tooltip: { trigger: 'selection' },
                        pieHole: 0.4,
                        legend: {
                            textStyle: {
                                fontName: 'Raleway',
                                fontSize: '12'
                            }
                        },
                        slices: {
                            0: { color: '#8ac594' },
                            1: { color: '#79bc84' },
                            2: { color: '#68b475' },
                            3: { color: '#57ab65' },
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('income-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
