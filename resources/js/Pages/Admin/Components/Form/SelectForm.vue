<script setup>
const model = defineModel()
const { selectName, Objects, validateMessage } = defineProps({
    selectName: String,
    Objects: Array,
    validateMessage: null

})
</script>

<template>
    <label for="countries_multiple" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        <slot />
    </label>
    <select v-model="model" :id="selectName"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="" selected disabled>Choose {{ selectName }}</option>
        <option v-for="Object in Objects" :key="Object.id" :value="Object.id">{{ Object.name }}
        </option>
    </select>
    <span class="text-sm text-red-500" v-if="validateMessage">{{ validateMessage }}</span>

</template>