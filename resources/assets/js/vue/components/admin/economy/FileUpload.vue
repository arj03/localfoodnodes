<template>
    <div class="card mb-5">
        <div class="card-header">File upload</div>
        <div class="card-body">
            <form id="upload-form">
                <div class="form-group">
                    <input type="file" name="file" class="input-file" v-on:change="updateFile" />
                </div>
                <div class="form-group">
                    <button class="btn btn-success" v-on:click="submit" v-show="file">Upload transactions</button>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
    export default {
        data: function() {
            return {
                file: false,
            }
        },
        methods: {
            updateFile() {
                let file = document.querySelector('#upload-form input[type=file]');

                if (file.files[0]) {
                    this.file = file.files[0];
                }
            },
            submit(event) {
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
                });
            }
        }
    }
</script>
