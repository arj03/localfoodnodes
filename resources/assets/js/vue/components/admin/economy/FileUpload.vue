<template>
    <div class="card mb-5">
        <div class="card-header">File upload</div>
        <div class="card-body">
            <i v-show="uploadInProgress" class="fa fa-spinner fa-spin"></i>
            <form v-show="!uploadInProgress" id="upload-form">
                <div class="form-group">
                    <input type="file" name="file" class="input-file" v-on:change="updateFile" />
                </div>
                <div class="form-group">
                    <button class="btn btn-success" v-on:click="submit" v-show="enableSubmit">Upload transactions</button>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['uploadFile', 'uploadInProgress'],
        data: function() {
            return {
                enableSubmit: false,
            }
        },
        watch: {
            uploadInProgress(status) {
                this.uploadInProgress = status;
                if (status === false) {
                    this.enableSubmit = false;
                }
            }
        },
        methods: {
            updateFile() {
                this.enableSubmit = true;
            },
            submit(event) {
                this.uploadFile(event);
            }
        }
    }
</script>
