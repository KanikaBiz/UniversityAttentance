  <template>
    <div class="card shadow p-4">
      <form @submit.prevent="submit">
        <div class="row">
          <!-- Name -->
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label fw-bold">Name</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="form-control"
              :class="{ 'is-invalid': errors.name }"
              required
            />
            <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
          </div>

          <!-- Category -->
          <div class="col-md-6 mb-3">
            <label for="category_id" class="form-label fw-bold">Category</label>
            <select
              v-model="form.category_id"
              id="category_id"
              class="form-select"
              :class="{ 'is-invalid': errors.category_id }"
              required
            >
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
            <div v-if="errors.category_id" class="invalid-feedback">
              {{ errors.category_id }}
            </div>
          </div>

          <!-- Unit Price -->
          <div class="col-md-6 mb-3">
            <label for="unit_price" class="form-label fw-bold">Unit Price</label>
            <input
              v-model="form.unit_price"
              type="number"
              step="0.01"
              id="unit_price"
              class="form-control"
              :class="{ 'is-invalid': errors.unit_price }"
              required
            />
            <div v-if="errors.unit_price" class="invalid-feedback">
              {{ errors.unit_price }}
            </div>
          </div>

          <!-- Sale Price -->
          <div class="col-md-6 mb-3">
            <label for="sale_price" class="form-label fw-bold">Sale Price</label>
            <input
              v-model="form.sale_price"
              type="number"
              step="0.01"
              id="sale_price"
              class="form-control"
              :class="{ 'is-invalid': errors.sale_price }"
            />
            <div v-if="errors.sale_price" class="invalid-feedback">
              {{ errors.sale_price }}
            </div>
          </div>

          <!-- Description -->
          <div class="col-md-12 mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea
              v-model="form.description"
              id="description"
              class="form-control"
              :class="{ 'is-invalid': errors.description }"
              rows="4"
            ></textarea>
            <div v-if="errors.description" class="invalid-feedback">
              {{ errors.description }}
            </div>
          </div>

          <!-- Image -->
          <div class="col-md-12 mb-3">
            <label for="image" class="form-label fw-bold">Image</label>
            <input
              type="file"
              id="image"
              class="form-control"
              accept="image/*"
              @change="handleFileChange"
              :class="{ 'is-invalid': errors.image }"
            />
            <div v-if="errors.image" class="invalid-feedback">{{ errors.image }}</div>
            <div
              class="mt-3 border p-3 rounded bg-light"
              v-if="imagePreview || existingImage"
            >
              <div class="d-flex flex-wrap gap-3">
                <div v-if="imagePreview">
                  <p class="fw-bold mb-1">New Image Preview:</p>
                  <img
                    :src="imagePreview"
                    alt="Image Preview"
                    class="img-thumbnail"
                    style="max-width: 200px"
                  />
                </div>
                <div v-if="existingImage">
                  <p class="fw-bold mb-1">Current Image:</p>
                  <img
                    :src="existingImage"
                    alt="Current Image"
                    class="img-thumbnail"
                    style="max-width: 200px"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-6 mb-3">
            <label for="status" class="form-label fw-bold">Status</label>
            <select
              v-model="form.status"
              id="status"
              class="form-select"
              :class="{ 'is-invalid': errors.status }"
              required
            >
              <option :value="1">Active</option>
              <option :value="0">Inactive</option>
            </select>
            <div v-if="errors.status" class="invalid-feedback">
              {{ errors.status }}
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end mt-4">
          <button type="submit" class="btn btn-primary btn-lg rounded">
            {{ submitButtonText }}
          </button>
        </div>
      </form>
    </div>
  </template>

  <script setup>
  import { reactive, computed, ref } from "vue";
  import { router, usePage } from "@inertiajs/vue3";

  const { product, categories, submitButtonText } = defineProps({
    product: { type: Object, default: null },
    categories: Array,
    submitButtonText: { type: String, default: "Submit" },
  });

  const page = usePage();
  const errors = computed(() => page.props.errors);

  const form = reactive({
    name: product ? product.name : "",
    description: product ? product.description : "",
    unit_price: product ? product.unit_price : 0,
    sale_price: product ? product.sale_price : null,
    image: null,
    status: product ? product.status : true,
    category_id: product ? product.category_id : null,
  });

  const imagePreview = ref(null);
  const existingImage = computed(() => product?.image_url || null);

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.image = file || null;
    if (file) {
      imagePreview.value = URL.createObjectURL(file);
    } else {
      imagePreview.value = null;
    }
  };

  const submit = () => {
    const formData = new FormData();
    Object.keys(form).forEach((key) => {
      if (form[key] !== null) {
        formData.append(key, form[key]);
      }
    });

    if (product) {
      formData.append("_method", "PUT");
      router.post(route("products.update", product.id), formData, {
        forceFormData: true,
      });
    } else {
      router.post(route("products.store"), formData, {
        forceFormData: true,
      });
    }
  };
  </script>
