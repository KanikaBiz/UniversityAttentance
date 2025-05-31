<template>
  <div>
    <h1 class="mb-4">Create Product</h1>
    <ProductForm :categories="categories" submitButtonText="Create" />
  </div>
</template>

<script setup>
import ProductForm from "../../Components/ProductForm.vue";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  categories: Array,
});

defineOptions({
  layout: MasterLayout,
});
</script>
