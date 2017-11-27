<template>
    <tr>
        <td>{{ order.id }}</td>
        <td>{{ order.item.node.name }}</td>
        <td>{{ order.item.product.name }}</td>
        <td>{{ order.created_at }}</td>
        <td>{{ order.quantity * order.item.product.price }}</td>
    </tr>
</template>

<script>
    export default {
        props: ['order'],
    }
</script>
