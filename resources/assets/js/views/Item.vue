<template>
    <div class="flex flex-wrap">
        <div class="rounded rounded-t-lg overflow-hidden shadow max-w-md my-3 bg-grey-dark">
            <img v-cloak :src=this.itemScreenshot :alt=this.item.displayProperties.name class="w-full">
            <div class="flex justify-center -mt-8 w-32 h-32">
                <img v-cloak :src=this.itemIcon :alt=this.item.displayProperties.name class="rounded-full border-solid border-white border-2 -mt-3">
            </div>
            <div class="text-center px-3 pb-4 pt-2">
                <h3 class="text-white text-md bold font-sans">{{ this.item.displayProperties.name }}</h3>
                <p class="mt-2 font-sans font-medium text-white">
                    {{ this.item.displayProperties.description }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['id'],

        data() {
            return {
                loading: false,
                item: {
                    displayProperties: {}
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
                    .get('/api/items/' + this.id)
                    .then(response => {
                        this.item = response.data.data;
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
                return 'https://www.bungie.net' + this.item.displayProperties.icon;
            },

            itemScreenshot() {
                return 'https://www.bungie.net' + this.item.screenshot;
            }
        }
    }
</script>

<style scoped>

</style>
