<script setup>
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import { router } from '@inertiajs/vue3';
import { ref } from "vue";

const { productImage, productData } = defineProps({
    productImage: Array,
    productData: Object,
})

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginFilePoster,
);

const myFiles = ref([]);


const handleFilePondInit = () => {
    console.log(productImage)
    if (productImage.length > 0) {
        const files = productImage.map(imagePath => ({
            source: `${imagePath}`,
            options: {
                type: 'local',
                metadata: {
                    poster: `${imagePath}`
                }
            }
        }));

        myFiles.value = files;
    } else {
        myFiles.value = [];
    }
};

const handleFilePondLoad = (response) => {
    const decodeResponse = JSON.parse(response)
    productData.product_images.push(atob(decodeResponse))
    return decodeResponse
}

const handleFilePondRevert = async (uniqueId, load, error) => {
    console.log('hello', uniqueId)
    productData.product_images = productData.product_images.filter(image => image !== uniqueId)
    await router.delete(`products/revert-image/${uniqueId}`);
    load()
}

const handleFilePondRemove = async (source, load, error) => {
    const uniqueId = btoa(source)
    await router.delete(`products/revert-image/${uniqueId}`);

    load()
}
</script>

<template>
    <div class="md:gap-6">
        <div class="relative z-0 w-full mb-6 group">
            <FilePond name="image" ref="pond" v-bind:allow-multiple="true" accepted-file-types="image/*"
                v-bind:files="myFiles" v-on:init="handleFilePondInit" v-bind:server="{
                    url: '',
                    timeout: 7000,
                    process: {
                        url: 'products/upload-image',
                        method: 'POST',
                        onload: handleFilePondLoad,
                    },
                    remove: handleFilePondRemove,
                    revert: handleFilePondRevert,
                    headers: {
                        'X-CSRF-TOKEN': $page.props.csrf_token
                    },
                }" />
        </div>
    </div>
</template>