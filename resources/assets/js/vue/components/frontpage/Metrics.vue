<template>
    <div class="row">
        <div class="col-12 col-xl-6 d-flex justify-content-center">
            <income-graph :trans="trans"></income-graph>
        </div>
        <div class="col-12 col-xl-6 d-flex justify-content-center">
            <costs-graph :trans="trans"></costs-graph>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                trans: economyTrans,
            }
        },
        components: {
            'costs-graph': require('./CostsGraph'),
            'income-graph': require('./IncomeGraph'),
        },
    }
</script>
