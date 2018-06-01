<template>
    <div class="flex flex-wrap self-center">
        <div class="flex flex-wrap self-center">
            <table class="text-left m-4">
                <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Command
                    </th>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Description
                    </th>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="command in commands" v-bind:command="command" class="hover:bg-blue-lightest">
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white">
                        {{ command.name }}
                    </td>
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white">
                        {{ command.description }}
                    </td>
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white text-center">
                        <a href="#" @click="run(command)">Run</a>
                        <a href="#" @click="output(command)">Output</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap self-center">
            <table class="text-left m-4">
                <thead>
                <tr>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Timestamp
                    </th>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Output
                    </th>
                    <th class="py-4 px-6 bg-grey-lightest font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">
                        Status Code
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="output in outputs" v-bind:output="output" v-bind:class="{ 'bg-red': output.status !== 0 }">
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white">
                        {{ output.timestamp}}
                    </td>
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white break-normal">
                        {{ truncateOutput(output.output) }}
                    </td>
                    <td class="py-4 px-6 border-b border-grey-lighter bg-white text-center">
                        {{ output.status }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                loading: false,
                commands: [],
                outputs: [],
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
                    .get('/api/commands')
                    .then(response => {
                        this.commands = response.data.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            },

            run(command) {
                axios
                    .post('/api/commands/' + command.name + '/runs')
                    .then(response => {
                        let output = response.data.data;
                        output.timestamp = Date.now();

                        this.outputs.unshift(output);
                    })
                    .catch(error => {
                        this.error = error.response.data.message || error.message;
                    });
            },

            output() {
                console.log('Output implemented later');
            },

            truncateOutput(output) {
                if (output.length < 77) {
                    return output;
                }

                return output.substring(0, 74) + '...';
            },
        },
    }
</script>

<style scoped>

</style>
