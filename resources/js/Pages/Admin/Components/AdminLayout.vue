<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">

        <!-- Navar -->
         <Navbar/>

        <!-- Sidebar -->
         <Sidebar/>

        <slot/>
    </div>
</template>

<script setup>
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';
</script>
