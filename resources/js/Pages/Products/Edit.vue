<template>
  <div>
    <h1 class="mb-4">Edit Product</h1>
    <ProductForm :product="product" :categories="categories" submitButtonText="Update" />
  </div>
</template>

<script setup>
import ProductForm from "../../Components/ProductForm.vue";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  product: Object,
  categories: Array,
});

defineOptions({
  layout: MasterLayout,
});
</script>
