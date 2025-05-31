<!-- Edit Category Form -->
<template>
  <div>
    <h1 class="mb-4">Edit Category</h1>
    <CategoryForm :categories="categories" :submitButtonText="'Update'" />
  </div>
</template>

<script setup>
import CategoryForm from "../../Components/CategoryForm.vue";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  categories: Array,
});

defineOptions({
  layout: MasterLayout,
});
</script>

<style scoped></style>
