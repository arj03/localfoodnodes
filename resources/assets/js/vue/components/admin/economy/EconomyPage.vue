<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <file-upload :uploadFile="uploadFile" :uploadInProgress="uploadInProgress"></file-upload>
                <transaction-list :transactions="transactions" :categories="categories" :fetching="fetching"></transaction-list>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'transaction-list': require('./TransactionList'),
            'file-upload': require('./FileUpload'),
        },
        data: function() {
            return {
                fetching: true,
                uploadInProgress: false,
                transactions: null,
                categories: null,
            }
        },
        mounted() {
            this.fetch();
        },
        methods: {
            fetch() {
                this.fetching = true;
                axios.get('/admin/token')
                .then(response => {
                    return axios.get('/api/v1/economy/transactions', {
                        headers: {
                            'Authorization': 'Bearer ' + response.data
                        }
                    });
                })
                .then(response => {
                    this.transactions = response.data.transactions;
                    this.categories = response.data.categories;
                    this.fetching = false;
                });
            },
            uploadFile(event) {
                this.uploadInProgress = true;
                event.preventDefault();
                axios.get('/admin/token')
                .then(response => {
                    let file = document.querySelector('#upload-form input[type=file]');
                    let formData = new FormData();
                    formData.append('file', file.files[0]);

                    return axios.post('/api/v1/economy/transactions', formData, {
                        headers: {
                          'Content-Type': 'multipart/form-data',
                          'Authorization': 'Bearer ' + response.data
                        }
                    });
                })
                .then(response => {
                    this.uploadInProgress = false;
                    let file = document.querySelector('#upload-form input[type=file]');
                    file.value = '';
                    this.fetch();
                });
            }
        }
    }
</script>
