<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Footer from './Footer.vue';
import Header from './Header.vue';
import HeroSection from './HeroSection.vue';

onMounted(() => {
    initFlowbite();
})

</script>

<template>

    <!-- Header -->
    <Header />
    
    <!-- Hero Section -->
    <HeroSection />

    <!-- index -->
    <slot />

    <!-- footer -->
    <Footer />

</template>