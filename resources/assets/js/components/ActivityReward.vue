<template>
    <div class="my-3">
        <img v-cloak :src=rewardIcon :alt=reward.name class="rounded-full border-solid border-white border-2 w-16 h-16"><h3 class="text-white text-md bold font-sans">{{ reward.name }}</h3>
        <p class="mt-2 font-sans font-medium text-white">{{ reward.description }}</p>
    </div>
</template>

<script>
    export default {
        props: ["reward"],

        computed: {
            rewardIcon() {
                return 'https://www.bungie.net' + this.reward.icon;
            }
        }
    }
</script>

<style scoped>

</style>
