<template>
    <div class="col-12 mb-5">
        <div class="card">
            <div class="card-header">Orders by node</div>
            <div class="card-body">
                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                <div id="orders-by-node-chart" style="height: 600px"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['orders'],
        data: function() {
            return {
                loading: true,
                incomeTransactions: null,
            }
        },
        watch: {
            orders: function(orders) {
                let data = this.formatData(orders);
                this.draw(data);
                this.loading = false;
            }
        },
        methods: {
            formatData(orders) {
                let ordersByNode = _(orders).groupBy(order => {
                    return order.order_item_relationship[0].node.id;
                })
                .map(function(orders, nodeId) {

                    let node = orders[0].order_item_relationship[0].node.name;

                    let sum = _.sumBy(orders, order => {
                        let price = order.order_item_relationship[0].product.price;
                        let quantity = order.quantity;

                        return price * quantity;
                    });

                    // let categoryObject = _.find(categories, category => {
                    //     return category.id == categoryId;
                    // });
                    //
                    // let label = (categoryObject && categoryObject.label) ? categoryObject.label : 'Uncategorized';

                    return [node, orders.length, sum]; // Convert to positive
                })
                .value();

                console.log(ordersByNode);

                // // Add headers
                ordersByNode.unshift(['Node', 'Number of orders', 'Money']);

                return ordersByNode;
            },
            draw(dataArray) {
                google.charts.load('current', {packages:['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var dataTable = google.visualization.arrayToDataTable(dataArray);

                    var options = {
                        chartArea: {
                            height: '60%',
                        },
                        series: {
                            0: {targetAxisIndex: 0},
                            1: {targetAxisIndex: 1}
                        },
                        vAxes: {
                            // Adds titles to each axis.
                            0: {title: 'Orders'},
                            1: {title: 'SEK'}
                        },
                        hAxis: {
                            slantedText: true,
                            slantedTextAngle: 45
                        }
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('orders-by-node-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
