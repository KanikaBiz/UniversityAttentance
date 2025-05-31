<template>
  <div>
    <h1 class="mb-4 text-center">Products</h1>
    <div v-if="$page.props.success" class="alert alert-success">
      {{ $page.props.success }}
    </div>
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Unit Price</th>
          <th>Sale Price</th>
          <th>Image</th>
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products.data" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.category.name }}</td>
          <td>{{ product.unit_price }}</td>
          <td>{{ product.sale_price || "N/A" }}</td>
          <td class="text-center">
            <img
              v-if="product.image_url"
              :src="product.image_url"
              alt="Product Image"
              class="img-thumbnail"
              style="max-width: 100px; max-height: 100px"
            />
            <span v-else class="text-muted">No Image</span>
          </td>
          <td>{{ product.status ? "Active" : "Inactive" }}</td>
          <td class="text-center">
            <div class="btn-group" role="group">
              <Link
                :href="route('products.show', product.id)"
                class="btn btn-sm btn-info rounded me-1"
                >View</Link
              >
              <Link
                :href="route('products.edit', product.id)"
                class="btn btn-sm btn-warning rounded me-1"
                >Edit</Link
              >
              <button
                @click="deleteProduct(product.id)"
                class="btn btn-sm btn-danger rounded"
              >
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <nav>
      <ul class="pagination justify-content-center">
        <li
          v-for="link in products.links"
          :key="link.label"
          class="page-item"
          :class="{ active: link.active }"
        >
          <Link
            v-if="link.url"
            :href="link.url"
            class="page-link"
            v-html="link.label"
          ></Link>
          <span v-else class="page-link" v-html="link.label"></span>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import MasterLayout from "../../Layouts/MasterLayout.vue";

defineProps({
  products: Object,
});

defineOptions({
  layout: MasterLayout,
});

const deleteProduct = (id) => {
  if (confirm("Are you sure you want to delete this product?")) {
    router.delete(route("products.destroy", id));
  }
};
</script>
