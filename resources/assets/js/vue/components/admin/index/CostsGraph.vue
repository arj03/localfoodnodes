<template>
    <div class="col-12 col-lg-6 mb-5">
        <div class="card">
            <div class="card-header">Costs</div>
            <div class="card-body">
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                <div id="costs-chart"></div>
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
                let incomes = _(transactions).filter(transaction => {
                    return transaction.amount < 0;
                }).groupBy(transaction => {
                    return transaction.category;
                }).map(function(category, categoryId) {
                    let sum = _.sumBy(category, transactions => {
                        return transactions.amount;
                    });

                    let categoryObject = _.find(categories, category => {
                        return category.id == categoryId;
                    });

                    let label = (categoryObject && categoryObject.label) ? categoryObject.label : 'Uncategorized';

                    return [label, -sum]; // Convert to positive
                })
                .value();

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
                            width: '80%',
                            height: '80%',
                        },
                        pieHole: 0.5,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('costs-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
