<!-- Form Create Category -->
<template>
  <div>
    <h1 class="mb-4">Create Category</h1>
    <CategoryForm :categories="categories" submitButtonText="Create" />
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
