<template>
    <div class="card mb-5">
        <div class="card-header">User singups</div>
        <div class="card-body">
            <i v-show="loading" class="fa fa-spinner fa-spin loader"></i>
            <div id="user-signup-chart" class="chart" style="height: 300px;"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data: function() {
            return {
                loading: true,
            }
        },
        watch: {
            data: function(data) {
                if (data) {
                    let graphData = this.formatData(data);
                    this.draw(graphData);
                    this.loading = false;
                }
            }
        },
        methods: {
            getEntityData(type, data) {
                let entitiesData = data[type];

                let entityByMonth = _(entitiesData).sortBy(entity => {
                    return moment(entity.created_at).unix();
                })
                .groupBy(entity => {
                    return moment(entity.created_at).format('MMMM YYYY');
                })
                .map(function(entities, date) {
                    return [date, entities.length];
                })
                .value();

                return entityByMonth
            },
            formatData(data) {
                // Users
                let usersByMonth = this.getEntityData('users', data);
                let producersByMonth = this.getEntityData('producers', data);
                let nodesByMonth = this.getEntityData('nodes', data);

                let zipped = _.zipWith(usersByMonth, producersByMonth, nodesByMonth, (u, p, n) => {
                    return [u[0], u[1], p[1], n[1]];
                });

                // Add headers
                zipped.unshift(['Date', 'Users', 'Producers', 'Nodes']);

                return zipped;
            },
            draw(dataArray) {
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart.bind(this));

                function drawChart() {
                    var options = {


                        isStacked: true,
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('user-signup-chart'));

                    let dataTable = google.visualization.arrayToDataTable(dataArray);
                    chart.draw(dataTable, options);
                }
            },
        },
    }
</script>
