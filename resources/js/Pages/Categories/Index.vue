<!-- List all Categories using Table-->
<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Categories</h1>
   <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
            <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="category in categories.data" :key="category.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ category.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ category.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <Link :href="route('categories.edit', category.id)" class="btn btn-sm btn-primary rounded me-1">Edit</Link>
                <Link
                :href="route('categories.show', category.id)"
                class="btn btn-sm btn-warning rounded me-1"
                >Show</Link>
                <button @click="deleteCategory(category.id)" class="btn btn-sm btn-danger">Delete</button>
            </td>
            </tr>
        </tbody>
    </table>
  </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  categories: Object,
});

defineOptions({
  layout: MasterLayout,
});

const deleteCategory = (id) => {
  if (confirm("Are you sure you want to delete this category?")) {
    router.delete(route("categories.destroy", id));
  }
};
</script>
