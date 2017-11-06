<template>
    <tr>
        <td>{{ order.id }}</td>
        <td>{{ order.order_item_relationship[0].node.name }}</td>
    </tr>
</template>

<script>
    export default {
        props: ['order'],
    }
</script>
