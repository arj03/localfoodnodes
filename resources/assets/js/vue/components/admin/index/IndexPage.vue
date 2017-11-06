<template>
    <div class="container">
        <div class="row">
            <income-graph></income-graph>
            <costs-graph></costs-graph>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'costs-graph': require('./CostsGraph'),
            'income-graph': require('./IncomeGraph'),
        },
    }
</script>
