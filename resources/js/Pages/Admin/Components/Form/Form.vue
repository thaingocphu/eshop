<script setup>

import { router, usePage } from '@inertiajs/vue3';
import InputForm from './InputForm.vue';
import TextareaForm from './TextareaForm.vue';
import SelectForm from './SelectForm.vue';
import FilePondForm from './FilePondForm.vue';
import ButtonEvent from '../ButtonEvent.vue';
import { computed } from 'vue';
import { useVuelidate } from '@vuelidate/core'
import { helpers , required } from '@vuelidate/validators'

const { editMode, productData, productImage, dialogVisible } = defineProps({
    editMode: Boolean,
    productData: Object,
    productImage: Array,
    dialogVisible: Boolean
})

const { brands, categories } = usePage().props

const emit = defineEmits(['updateVisible'])

const rules = computed(() => {
    return {
        category_id: { required : helpers.withMessage('You must select product category', required) },
        brand_id: { required: helpers.withMessage('You must select product brand', required) },
    }
})

const v$ = useVuelidate(rules, productData)

const handleSubmit = async () => {
    try {
        const result = await v$.value.$validate()
        if (!result) {
            console.log("form error!!!")
            return
        } else {
            if (editMode) {
                await UpdateProduct()
            } else {
                await AddProduct()
            }
        }
    } catch (err) {
        console.log(err)
    }
}

const callUpdateVisible = () => {
    emit('updateVisible')
}

const AddProduct = async () => {
    const formData = new FormData();
    for (const key in productData) {
        if (key == "product_images") {
            for (let i = 0; i < productData.product_images.length; i++) {
                formData.append('product_images[]', productData.product_images[i])
            }
        } else {
            formData.append(key, productData[key])
        }
    }
    try {
        await router.post('products/store', formData, {
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    timer: 3000,
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                callUpdateVisible();
            }
        })
    } catch (err) {
        console.log(err)
    }
}

const UpdateProduct = async () => {
    const formData = new FormData();
    for (const key in productData) {
        if (key == "product_images") {
            for (let i = 0; i < productData.product_images.length; i++) {
                formData.append('product_images[]', productData.product_images[i])
            }
        } else {
            formData.append(key, productData[key])
        }
    }
    formData.append('_method', 'PUT')

    try {
        await router.post(`products/update/${productData.id}`, formData, {
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    timer: 3000,
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                callUpdateVisible();
            }
        })
    } catch (err) {
        console.log(err)
    }
}

</script>

<template>
    <form @submit.prevent="handleSubmit()" class="max-w-md mx-auto">

        <InputForm v-model="productData.title" inputName="title" type="text">Title</InputForm>
        <InputForm v-model="productData.price" inputName="price" type="number">Price</InputForm>
        <InputForm v-model="productData.quantity" inputName="quantity" type="number">Quantity</InputForm>

        <SelectForm v-model="productData.brand_id" selectName="brand" :Objects="brands"
            :validateMessage="v$.brand_id.$error ? v$.brand_id.$errors[0].$message : null">Brand</SelectForm>
        <SelectForm v-model="productData.category_id" selectName="category" :Objects="categories"
            :validateMessage="v$.category_id.$error ? v$.category_id.$errors[0].$message : null">Category</SelectForm>

        <TextareaForm v-model="productData.description" rows="4" textareaName="description">Description</TextareaForm>

        <FilePondForm v-if="dialogVisible" :productImage :productData />

        <div class="flex justify-between">
            <ButtonEvent @click.prevent="callUpdateVisible" colorName="red">Cancel</ButtonEvent>
            <ButtonEvent colorName="blue" type="submit">Submit</ButtonEvent>
        </div>
    </form>

</template>