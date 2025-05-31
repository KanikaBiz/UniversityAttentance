<!-- Create Category Form -->
<template>
  <form @submit.prevent="submit" class="row g-3">
    <div class="row">
        <div class="col-md-6">
            <label for="name" class="form-label fw-bold">Category Name</label>
            <input
                v-model="form.name"
                type="text"
                id="name"
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                required
            />
        </div>
        <div class="col-md-6">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea
                v-model="form.description"
                id="description"
                class="form-control"
                :class="{ 'is-invalid': errors.description }"
            ></textarea>
            <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
        </div>
        <!-- image -->
        <div class="col-md-6">
            <label for="image" class="form-label fw-bold">Image</label>
            <input
                type="file"
                id="image"
                class="form-control"
                @change="handleFileChange"
            />
        </div>
        <!-- Status -->
        <div class="col-md-6">
            <label for="status" class="form-label fw-bold">Status</label>
            <select v-model="form.status" id="status" class="form-select" :class="{ 'is-invalid': errors.status }">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <div v-if="errors.status" class="invalid-feedback">{{ errors.status }}</div>
        </div>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Save Category</button>
    </div>
  </form>
</template>

<script setup>
  import { reactive, computed, ref } from "vue";
  import { router, usePage } from "@inertiajs/vue3";

    const props = defineProps({
        categories: Object,
    });
    const form = reactive({
        name: props.categories ? props.categories.name : "",
        description: props.categories ? props.categories.description : "",
        image: null,
        status: props.categories ? props.categories.status : 1,
    });
    const errors = computed(() => usePage().props.errors || {});
    const categories = props.categories || null;
    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            form.image = file;
        } else {
            form.image = null;
        }
        };
    // Handle form submission
  const submit = () => {
    const formData = new FormData();
    Object.keys(form).forEach((key) => {
      if (form[key] !== null) {
        formData.append(key, form[key]);
      }
    });

    if (categories) {
      formData.append("_method", "PUT");
      router.post(route("categories.update", categories.id), formData, {
        forceFormData: true,
      });
    } else {
      router.post(route("categories.store"), formData, {
        forceFormData: true,
      });
    }
  };

  </script>
