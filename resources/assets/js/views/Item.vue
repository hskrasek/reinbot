<template>
    <div class="flex flex-wrap self-center">
        <div class="rounded rounded-t-lg overflow-hidden shadow w-3/5 mx-3 my-3 bg-grey-dark">
            <img v-cloak :src=itemScreenshot :alt=item.name class="w-full" v-if="item.screenshot">
            <div class="flex justify-center -mt-8">
                <img v-cloak :src=itemIcon :alt=item.name class="rounded-full border-solid border-white border-2 -mt-3 w-32 h-32">
            </div>
            <div class="text-center px-3 pb-4 pt-2">
                <h3 class="text-white text-md bold font-sans">{{ item.name }}</h3>
                <p class="mt-2 font-sans font-medium text-white">
                    {{ item.description }}
                </p>
            </div>
        </div>
        <div class="rounded rounded-t-lg overflow-hidden shadow w-1/5 mx-3 my-3 bg-grey-dark">
            <div class="text-center px-3 pb-4 pt-2">
                <img v-for="socket in item.sockets" :src=socketIcon(socket) :alt=socket.name class="rounded-full border-solid border-white border-2 w-16 h-16 mr-2">
            </div>
        </div>
        <div class="rounded rounded-t-lg overflow-hidden shadow max-w mx-3 my-3 bg-grey-dark" v-if="item.lore">
            <div class="text-center px-3 pb-4 pt-2">
                <h3 class="text-white text-md bold font-sans">Lore</h3>
                <p class="mt-2 font-sans font-medium text-white" v-html="formattedLore">
                    {{ formattedLore }}
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
                item: {},
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
            },

            socketIcon(socket) {
                return 'https://www.bungie.net' + socket.icon;
            },
        },

        computed: {
            itemIcon() {
                return 'https://www.bungie.net' + this.item.icon;
            },

            itemScreenshot() {
                return 'https://www.bungie.net' + this.item.screenshot;
            },

            formattedLore() {
                return this.item.lore.description.replace(/(?:\r\n|\r|\n)/g, '<br>');
            },
        }
    }
</script>

<style scoped>

</style>
