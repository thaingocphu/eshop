<script setup>

import { router } from '@inertiajs/vue3';
import ButtonEvent from '../ButtonEvent.vue';


const {products} = defineProps({
    products: Array,
})

const emit = defineEmits(['openEditModel'])

const callOpenEditModel = (product) => {
    emit('openEditModel', product)
}

const deleteProduct = (product) => {
    Swal.fire({
        title: 'Do you want to delete product?',
        text: 'This action can not undo',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete(`products/destroy/${product.id}`)
            } catch (err) {
                console.log(err)
            }
        }
    })
}
</script>

<template>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-4 py-3">Product name</th>
                <th scope="col" class="px-4 py-3">Category</th>
                <th scope="col" class="px-4 py-3">Brand</th>
                <th scope="col" class="px-4 py-3">Quantity</th>
                <th scope="col" class="px-4 py-3">Price</th>
                <th scope="col" class="px-4 py-3">Stock</th>
                <th scope="col" class="px-4 py-3">Publish</th>
                <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(product, index) in products" :key="product.id" class="border-b dark:border-gray-700">
                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                    product.title }}</th>
                <td class="px-4 py-3">{{ product.category.name }}</td>
                <td class="px-4 py-3">{{ product.brand.name }}</td>
                <td class="px-4 py-3">{{ product.quantity }}</td>
                <td class="px-4 py-3">{{ product.price }}</td>
                <td class="px-4 py-3">
                    <span v-if="product.inStock == 0"
                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">Out
                        of stock</span>
                    <span v-else
                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">in
                        stock</span>
                </td>
                <td class="px-4 py-3">

                    <ButtonEvent v-if="product.published == 1" colorName="green">Published </ButtonEvent>
                    <ButtonEvent  v-else colorName="red">Private</ButtonEvent>
                </td>
                <td class="px-4 py-3 flex items-center justify-end gap-x-3">
                    <ButtonEvent  @click.prevent="callOpenEditModel(product)" colorName="blue">Edit</ButtonEvent>
                    <ButtonEvent @click.prevent="deleteProduct(product, index)" colorName="red">Delete</ButtonEvent>
                </td>
            </tr>
        </tbody>
    </table>
</template>