<script setup>
const { colorName } = defineProps({
    colorName: String,
})
</script>
<template>

    <button :class="`text-white bg-${colorName}-700 hover:bg-${colorName}-800 focus:ring-4 focus:outline-none focus:ring-${colorName}-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-${colorName}-600 dark:hover:bg-${colorName}-700 dark:focus:ring-${colorName}-800`">
        <slot />
    </button>

</template>