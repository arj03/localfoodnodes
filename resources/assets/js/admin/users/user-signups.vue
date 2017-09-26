<template>
    <div class="card mb-5">
        <div class="card-header">User singups</div>
        <div class="card-body">
            <div id="user-signup-chart"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['users'],
        mounted() {
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart.bind(this));

            let users = _.sortedIndexBy(this.users, 'created_at');

            function drawChart() {
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

                var options = {
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('user-signup-chart'));

                data = google.visualization.arrayToDataTable(data);
                chart.draw(data, options);
            }
        },
    }
</script>
