<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 mb-5">
                <div class="card">
                    <div class="card-header">Income</div>
                    <div class="card-body">
                        <income-graph></income-graph>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-5">
                <div class="card">
                    <div class="card-header">Costs</div>
                    <div class="card-body">
                        <costs-graph></costs-graph>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'costs-graph': require('../economy/CostsGraph'),
            'income-graph': require('../economy/IncomeGraph'),
        },
    }
</script>
