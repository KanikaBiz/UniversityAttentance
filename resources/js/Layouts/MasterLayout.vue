<template>
  <div>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <Link class="navbar-brand" :href="route('products.index')">Product CRUD</Link>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <!-- categories -->
            <li class="nav-item">
              <Link class="nav-link" :href="route('categories.index')" :class="{ 'active': $page.url.startsWith('/categories') && !$page.url.includes('create') }">Categories</Link>
            </li>
            <li class="nav-item">
              <Link class="nav-link" :href="route('categories.create')" :class="{ 'active': $page.url.includes('create') }">Create Category</Link>
            </li>
            <li class="nav-item">
              <Link class="nav-link" :href="route('products.index')" :class="{ 'active': $page.url.startsWith('/products') && !$page.url.includes('create') }">Products</Link>
            </li>
            <li class="nav-item">
              <Link class="nav-link" :href="route('products.create')" :class="{ 'active': $page.url.includes('create') }">Create Product</Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
</script>

<style scoped>
.navbar-brand {
  font-weight: bold;
}
.nav-link.active {
  font-weight: bold;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
}
</style>
