<template>
    <tr>
        <td>{{ transaction.date }}</td>
        <td>{{ transaction.ref }}</td>
        <td>{{ transaction.description }}</td>
        <td class="text-success" v-bind:class="{ 'text-danger': isNegative(transaction.amount) }">{{ transaction.amount }}</td>
        <td>
            <select class="form-control" v-model="selectedCategory" @change="updateCategory">
                <option value="-1">Select a category</option>
                <option v-for="category in categories" :value="category.id">{{ category.label }}</option>
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
        props: ['transaction', 'categories'],
        data: function() {
            return {
                loading: false,
                error: false,
                success: false,
                selectedCategory: this.transaction.category || -1
            }
        },
        methods: {
            isNegative(number) {
                return Math.sign(number) < 1 ? true : false;
            },
            updateCategory(event) {
                this.loading = true;

                let data = {
                    id: this.transaction.id,
                    category: event.target.value,
                }

                axios.get('/admin/token')
                .then(response => {
                    return axios.put('/api/v1/economy/transactions', data, {
                        headers: {
                            'Authorization': 'Bearer ' + response.data.access_token
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
