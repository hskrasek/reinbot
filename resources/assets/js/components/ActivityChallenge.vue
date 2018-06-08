<template>
    <div class="my-3">
        <h3 class="text-white text-md bold font-sans">{{ challenge.name }}</h3>
        <p class="mt-2 font-sans font-medium text-white">{{ challenge.description }}</p>
    </div>
</template>

<script>
    export default {
        props: ['challenge']
    }
</script>

<style scoped>

</style>
