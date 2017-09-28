<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <order-list></order-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'order-list': require('./OrderList'),
        },
    }
</script>
