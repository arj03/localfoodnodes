<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <file-upload></file-upload>
                <transaction-list></transaction-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'transaction-list': require('./TransactionList'),
            'file-upload': require('./FileUpload'),
        }
    }
</script>
