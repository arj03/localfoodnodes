<template>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>
            <select class="form-control" v-bind:class="{ 'text-danger': !active }" v-model="active" @change="updateActive">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </td>
        <td class="status">
            <i v-show="loading" class="fa fa-spinner fa-spin"></i>
            <i v-show="!loading && error" class="fa fa-exclamation-circle text-danger"></i>
            <i v-show="!loading && success" class="fa fa-check-circle text-success"></i>
        </td>
    </tr>
</template>

<style scoped>
    td {
        vertical-align: middle;
    }
    td.status {
        width: 40px;
    }
</style>

<script>
    export default {
        props: ['user'],
        data: function() {
            return {
                loading: false,
                error: false,
                success: false,
                active: 0
            }
        },
        mounted() {
            this.active = this.user.active ? 1 : 0;
        },
        methods: {
            updateActive(event) {
                this.loading = true;

                let data = {
                    id: this.user.id,
                    fields: {
                        active: event.target.value,
                    }
                };

                axios.get('/admin/token')
                .then(response => {
                    return axios.put('/api/v1/users', data, {
                        headers: {
                            'Authorization': 'Bearer ' + response.data
                        }
                    });
                })
                .then(response => {
                    this.loading = false;
                    this.success = true;
                })
                .catch(error => {
                    console.error(error);
                    this.loading = false;
                    this.error = true;
                });
            }
        }
    }
</script>
