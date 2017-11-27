<template>
    <div class="col-12 mb-5">
        <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
        <div id="orders-by-node-chart" class="chart" style="height: 600px"></div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                loading: true,
            }
        },
        mounted: function() {
            axios.get('/api-proxy', {
                params: {
                    url: '/api/v1/orders/'
                }
            })
            .then(response => {
                let ordersByNode = this.formatData(response.data);
                this.draw(ordersByNode);
                this.loading = false;
            });
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

                    return [node, orders.length, sum]; // Convert to positive
                })
                .value();

                // Add headers
                ordersByNode.unshift(['Node', 'Number of orders', 'Money circulated locally (SEK)']);

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
                        },
                        colors:['#d36262','#8ac594']
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('orders-by-node-chart'));
                    chart.draw(dataTable, options);
                }
            }
        }
    }
</script>
