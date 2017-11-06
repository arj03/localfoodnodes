<template>
    <div class="card mb-5">
        <div class="card-header">User singups</div>
        <div class="card-body">
            <i v-show="loading" class="fa fa-spinner fa-spin"></i>
            <div id="user-signup-chart"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['users'],
        data: function() {
            return {
                loading: true,
            }
        },
        watch: {
            users: function(users) {
                if (users) {
                    let data = this.formatData();
                    this.draw(data);
                }
            }
        },
        methods: {
            formatData() {
                let users = _.sortedIndexBy(this.users, 'created_at');

                let first = this.users[0];
                let last = this.users[_.size(this.users) - 1];
                let usersByCreatedAt = _.groupBy(this.users, (user) => {
                    return moment(user.created_at).format('YYYY-MM-DD');
                });

                let data = [
                    ['Date', 'Signups'],
                ];

                // If you want an exclusive end date (half-open interval)
                for (var m = moment(first.created_at); m.isBefore(last.created_at); m.add(1, 'days')) {
                    data.push([
                        m.format('YYYY-MM-DD'),
                        _.size(usersByCreatedAt[m.format('YYYY-MM-DD')])
                    ]);
                }

                return data;
            },
            draw(dataArray) {
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart.bind(this));

                function drawChart() {
                    var options = {
                        legend: { position: 'none' },
                        hAxis: { textPosition: 'none' }
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('user-signup-chart'));

                    let dataTable = google.visualization.arrayToDataTable(dataArray);
                    chart.draw(dataTable, options);
                    this.loading = false;
                }
            },
        },
    }
</script>
