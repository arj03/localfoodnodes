<template>
    <tr>
        <td>{{ transaction.date }}</td>
        <td>{{ transaction.ref }}</td>
        <td>{{ transaction.description }}</td>
        <td v-bind:class="{
            'text-danger': isNegative(transaction.amount),
            'text-success': !isNegative(transaction.amount)
            }">{{ transaction.amount }}</td>
        <td>{{ categoryName() }}</td>
    </tr>
</template>

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
