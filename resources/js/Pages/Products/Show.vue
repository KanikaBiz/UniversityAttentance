<template>
  <div>
    <h1 class="mb-4">Product Details</h1>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ product.name }}</h5>
        <p class="card-text"><strong>Category:</strong> {{ product.category.name }}</p>
        <p class="card-text">
          <strong>Description:</strong> {{ product.description || "N/A" }}
        </p>
        <p class="card-text"><strong>Unit Price:</strong> ${{ product.unit_price }}</p>
        <p class="card-text">
          <strong>Sale Price:</strong> ${{ product.sale_price || "N/A" }}
        </p>
        <p class="card-text">
          <strong>Status:</strong> {{ product.status ? "Active" : "Inactive" }}
        </p>
        <div v-if="product.image_url" class="mb-3">
          <strong>Image:</strong><br />
          <img
            :src="product.image_url"
            alt="Product Image"
            class="img-fluid"
            style="max-width: 300px"
          />
        </div>
        <div class="d-flex gap-2">
          <Link :href="route('products.index')" class="btn btn-secondary"
            >Back to List</Link
          >
          <Link :href="route('products.edit', product.id)" class="btn btn-warning"
            >Edit</Link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  product: Object,
});

defineOptions({
  layout: MasterLayout,
});
</script>
