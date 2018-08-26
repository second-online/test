<template>
    <div>
{{ msg.name }}
    </div>
</template>

<script>
    export default {
        name: 'broadcast-comment',
        props: ['msg']
    }
</script>