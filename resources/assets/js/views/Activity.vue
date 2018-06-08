<template>
    <div class="flex flex-wrap self-center">
        <div class="rounded rounded-t-lg overflow-hidden shadow w-3/5 mx-3 my-3 bg-grey-dark">
            <img v-cloak :src=activityScreenshot :alt=activity.name class="w-full" v-if="activity.screenshot">
            <div class="flex justify-center -mt-8">
                <img v-cloak :src=itemIcon :alt=activity.name
                     class="rounded-full border-solid border-white border-2 -mt-3 w-32 h-32">
            </div>
            <div class="text-center px-3 pb-4 pt-2">
                <h2 class="text-white text-md bold font-sans">{{ activity.name }}</h2>
                <h3 class="text-white text-md bold font-sans">{{ activity.destination.description }} ({{ activity.destination.name }})</h3>
                <p class="mt-2 font-sans font-medium text-white">
                    {{ activity.description }}
                </p>
            </div>
        </div>
        <div class="rounded rounded-t-lg overflow-hidden shadow w-1/5 mx-3 my-3 bg-grey-dark">
            <div class="px-3 pb-4 pt-2">
                <activity-challenge v-for="challenge in activity.challenges" :challenge="challenge" :key="challenge.id"></activity-challenge>
            </div>
        </div>
        <div class="rounded rounded-t-lg overflow-hidden shadow w-3/5 mx-3 my-3 bg-grey-dark">
            <div class="px-3 pb-4 pt-2">
                <activity-reward v-for="reward in activity.rewards" :reward="reward" :key="reward.id"></activity-reward>
            </div>
        </div>
        <div class="rounded rounded-t-lg overflow-hidden shadow w-1/5 mx-3 my-3 bg-grey-dark">
            <div class="px-3 pb-4 pt-2 text-white">
                <h2 class="text-center">Activity Mode</h2>
            </div>
            <div class="px-3 pb-4 pt-2 text-white">
                <div class="flex justify-center mt-8">
                    <img v-cloak :src=activityModeIcon :alt=activity.mode.name
                         class="rounded-full border-solid border-white border-2 -mt-3 w-32 h-32">
                </div>
                <div class="text-center px-3 pb-4 pt-2">
                    <h2 class="text-white text-md bold font-sans">{{ activity.mode.name }}</h2>
                    <p class="mt-2 font-sans font-medium text-white">
                        {{ activity.mode.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import ActivityChallenge from "../components/ActivityChallenge";
    import ActivityReward from "../components/ActivityReward";

    export default {
        components: {ActivityReward, ActivityChallenge},
        props: ['id'],

        data() {
            return {
                loading: false,
                activity: {
                    destination: {},
                    mode: {},
                },
                error: null,
            };
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = null;
                this.loading = true;
                axios
                    .get('/api/activities/' + this.id)
                    .then(response => {
                        this.activity = response.data.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            }
        },

        computed: {
            itemIcon() {
                return 'https://www.bungie.net' + this.activity.icon;
            },

            activityScreenshot() {
                return 'https://www.bungie.net' + this.activity.screenshot;
            },

            activityModeScreenshot() {
                return 'https://www.bungie.net' + this.activity.mode.screenshot;
            },

            activityModeIcon() {
                return 'https://www.bungie.net' + this.activity.mode.icon;
            }
        }
    }
</script>

<style scoped>

</style>
