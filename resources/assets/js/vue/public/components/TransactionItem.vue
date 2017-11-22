<template>
    <tr>
        <td>{{ transaction.date }}</td>
        <td>{{ transaction.ref.toLowerCase() }}</td>
        <td>{{ transaction.description }}</td>
        <td>
            <div v-bind:class="{
                'text-danger': isNegative(transaction.amount),
                'text-success': !isNegative(transaction.amount)
                }">
                {{ transaction.amount }}
            </div>
        </td>
        <td>{{ categoryName() }}</td>
    </tr>
</template>

<style scoped>
    .table td {
        font-size: 14px;
        text-transform: capitalize;
    }
</style>

<script>
    export default {
        props: ['transaction', 'categories'],
        methods: {
            isNegative(number) {
                return Math.sign(number) < 1 ? true : false;
            },
            categoryName() {
                let category = _.find(this.categories, (category) => {
                    return category.id == this.transaction.category;
                });

                return category.label;
            }
        }
    }
</script>
