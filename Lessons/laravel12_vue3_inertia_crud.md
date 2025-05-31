### Lesson: Building a Laravel 12 + Vue 3 + Inertia CRUD Application with Pagination

### Objective

By the end of this lesson, you will be able to:

-   Set up a Laravel 12 project with Inertia.js and Vue 3.
-   Create a `Product` table with migrations and relationships.
-   Implement CRUD operations (Create, Read, Update, Delete) for products.
-   Add pagination to the product listing.
-   Use Ziggy for Laravel route access in Vue components.
-   Style the application with Bootstrap 5.
-   Use the Vue 3 Composition API for reactive components.

### Prerequisites

-   Basic knowledge of Laravel, Vue.js, and MySQL.
-   Installed: PHP 8.2+, Composer, Node.js, npm, and a MySQL database.
-   A code editor (e.g., VS Code).

---

### Step-by-Step Process

#### Step 1: Set Up the Laravel Project

  **Goal**: Create a new Laravel 12 project and configure the environment.

  1. **Install Laravel 12**:
    Run the following command to create a new Laravel project:

      ```bash
      composer create-project --prefer-dist laravel/laravel laravel12-vue-intertia-product-crud
      cd  laravel12-vue-intertia-product-crud
      ```

  2. **Configure the Database**:

      - Open the `.env` file and set up your MySQL database credentials:
          ```
          DB_CONNECTION=mysql
          DB_HOST=127.0.0.1
          DB_PORT=3306
          DB_DATABASE=inertia_product_crud
          DB_USERNAME=root
          DB_PASSWORD=
          ```
      - Create the database `inertia_product_crud` in your MySQL client (e.g., phpMyAdmin or MySQL CLI).

  3. **Install Inertia.js Server-Side**:
    Install the Inertia.js Laravel adapter:

      ```bash
      composer require inertiajs/inertia-laravel
      ```

  4. **Set Up Inertia Middleware**:

      - Run the following command to publish the Inertia middleware:
          ```bash
          php artisan inertia:middleware
          ```
      - Register the middleware in `bootstrap/app.php`:

          ```php
          <?php
          use Illuminate\Foundation\Application;
          use Illuminate\Foundation\Configuration\Exceptions;
          use Illuminate\Foundation\Configuration\Middleware;
          use App\Http\Middleware\HandleInertiaRequests;

          return Application::configure(basePath: dirname(__DIR__))
              ->withRouting(
                  web: __DIR__.'/../routes/web.php',
                  commands: __DIR__.'/../routes/console.php',
                  health: '/up',
              )
              ->withMiddleware(function (Middleware $middleware) {
                  $middleware->web(append: [
                      HandleInertiaRequests::class,
                  ]);
              })
              ->withExceptions(function (Exceptions $exceptions) {
                  //
              })->create();
          ```

  5. **Install Front-End Dependencies**:

      - Install Vue 3, Inertia.js Vue adapter and Vitejs Plugin :
          ```bash
          npm install vue @inertiajs/vue3 @vitejs/plugin-vue
          ```
      - Install Bootstrap 5 and Bootstrap icons:
          ```bash
          npm install bootstrap bootstrap-icons
          ```

  6. **Set Up Vite Configuration**:
    Update `vite.config.js` to include Vue and configure the input files:

      ```javascript
      import { defineConfig } from "vite";
      import laravel from "laravel-vite-plugin";
      import vue from "@vitejs/plugin-vue";

      export default defineConfig({
          plugins: [
              laravel({
                  input: ["resources/js/app.js", "resources/css/app.css"],
                  refresh: true,
              }),
              vue({
                  template: {
                      transformAssetUrls: {
                          base: null,
                          includeAbsolute: false,
                      },
                  },
              }),
          ],
      });
      ```

  7. **Set Up the Main JavaScript Entry Point**:
    Create or update `resources/js/app.js` to initialize Vue and Inertia:

      ```javascript
      import "./bootstrap";
      import { createApp, h } from "vue";
      import { createInertiaApp } from "@inertiajs/vue3";
      import { ZiggyVue } from "../../vendor/tightenco/ziggy";
      import "../css/app.css";

      createInertiaApp({
          resolve: (name) => {
              const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
              return pages[`./Pages/${name}.vue`].default;
          },
          setup({ el, App, props, plugin }) {
              createApp({ render: () => h(App, props) })
                  .use(plugin)
                  .use(ZiggyVue)
                  .mount(el);
          },
      });
      ```

  8. **Include Bootstrap CSS**:
    Update `resources/css/app.css` to import Bootstrap:

      ```css
      @import "bootstrap/dist/css/bootstrap.min.css";
      @import "bootstrap-icons/font/bootstrap-icons.css";
      ```

  9. **Create the Root Template**:
    Update `resources/views/app.blade.php` to serve as the Inertia root template:
    if not have create one `resources/views/app.blade.php`

      ```html
      <!DOCTYPE html>
      <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1" />

          <title inertia>{{ config('app.name', 'Laravel') }}</title>
          <meta name="csrf-token" content="{{ csrf_token() }}" />
          <!-- Fonts -->
          <link rel="preconnect" href="https://fonts.bunny.net" />
          <link
              href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
              rel="stylesheet"
          />
          <!-- Scripts -->
          @routes 
          @vite(['resources/js/app.js',"resources/js/Pages/{$page['component']}.vue"]) 
          @inertiaHead
        </head>

        <body class="font-sans antialiased">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                @inertia
              </div>
          </div>
        </body>
      </html>
      ```

  10. **Install Ziggy**:

      - Install the Ziggy package to use Laravel routes in JavaScript:
          ```bash
          composer require tightenco/ziggy
          ```
      - The `@routes` directive in `app.blade.php` makes routes available to Ziggy.

  11. **Run Migrations and Start Servers**:
      - Run migrations (none exist yet, but this ensures the database is set up):
          ```bash
          php artisan migrate
          ```
      - Start the Laravel development server:
          ```bash
          php artisan serve
          ```
      - Start the Vite development server:
          ```bash
          npm run dev
          ```

  **Checkpoint**: Visit `http://localhost:8000` to ensure the Laravel welcome page loads (it will be replaced later).

  ---

#### Step 2: Create the Product and Category Models

  **Goal**: Set up the database schema and models for `Product` and `Category`.

  1. **Create the Category Model and Migration**:
    Run the following command to create the `Category` model with a migration:

      ```bash
      php artisan make:model Category -m
      ```

      Update the migration file in `database/migrations/xxxx_create_categories_table.php`:

      ```php
      <?php

      use Illuminate\Database\Migrations\Migration;
      use Illuminate\Database\Schema\Blueprint;
      use Illuminate\Support\Facades\Schema;

      return new class extends Migration
      {
          public function up(): void
          {
            Schema::create('categories', function (Blueprint $table) {
              $table->id();
              $table->string('name');
              $table->string('slug')->unique();
              $table->string('description')->nullable();
              $table->string('image')->nullable();
              $table->boolean('status')->default(true);
              $table->timestamps();
            });
          }

          public function down(): void
          {
              Schema::dropIfExists('categories');
          }
      };
      ```

  2. **Create the Product Model and Migration**:
    Run the following command to create the `Product` model with a migration:

      ```bash
      php artisan make:model Product -m
      ```

      Update the migration file in `database/migrations/xxxx_create_products_table.php`:

      ```php
      <?php

      use Illuminate\Database\Migrations\Migration;
      use Illuminate\Database\Schema\Blueprint;
      use Illuminate\Support\Facades\Schema;

      return new class extends Migration
      {
          public function up(): void
          {
              Schema::create('products', function (Blueprint $table) {
                  $table->id();
                  $table->string('name');
                  $table->string('slug')->unique();
                  $table->text('description')->nullable();
                  $table->decimal('unit_price', 8, 2);
                  $table->decimal('sale_price', 8, 2)->nullable();
                  $table->string('image')->nullable();
                  $table->boolean('status')->default(true);
                  $table->foreignId('category_id')->constrained()->onDelete('cascade');
                  $table->timestamps();
              });
          }

          public function down(): void
          {
              Schema::dropIfExists('products');
          }
      };
      ```

  3. **Define Model Relationships**:

      - Update `app/Models/Category.php` to define the relationship with `Product`:

          ```php
          <?php

          namespace App\Models;
          use Illuminate\Support\Str;
          use App\Models\Product;
          use Illuminate\Database\Eloquent\Model;

          class Category extends Model
          {
              protected $fillable = [
                  'name',
                  'slug',
                  'image',
                  'description',
                  'status',
              ];

              public function products()
              {
                  return $this->hasMany(Product::class);
              }

              protected $casts = [
                  'status' => 'boolean',
              ];

              protected static function boot()
              {
                  parent::boot();

                  static::creating(function ($category) {
                      if (empty($category->slug)) {
                          $category->slug = Str::slug($category->name);
                      }
                  });

                  static::updating(function ($category) {
                      if ($category->isDirty('name')) {
                          $category->slug = Str::slug($category->name);
                      }
                  });
              }
          }
          ```

      - Update `app/Models/Product.php` to define the relationship with `Category`:

          ```php
          <?php

          namespace App\Models;

          use App\Models\Category;
          use Illuminate\Support\Facades\Storage;
          use Illuminate\Database\Eloquent\Model;

          class Product extends Model
          {
            protected $fillable = [
              'name',
              'slug',
              'description',
              'unit_price',
              'sale_price',
              'image',
              'status',
              'category_id',
            ];

            protected $appends = ['image_url'];

            public function category()
            {
              return $this->belongsTo(Category::class);
            }

            public function getImageUrlAttribute()
            {
              if (!$this->image) {
                return asset('images/default_product_image.png');
              }
              // Check if the image is stored in the local disk
              if (Storage::disk('local')->exists($this->image)) {
                return Storage::url($this->image);
              }
              // If the image is not found in the local disk, return null
              if (Storage::disk('public')->exists($this->image)) {
                return Storage::url($this->image);
              }
              // If the image is not found in either disk, return null
              return $this->image ? Storage::url($this->image) : null;
            }
          }
          ```

  4. **Run Migrations**:

      ```bash
      php artisan migrate
      ```

  5. **Seed Sample Data**:
    Create a seeder for categories and products:

      ```bash
      php artisan make:seeder DataSeeder
      ```

      Update `database/seeders/DataSeeder.php`:

      ```php
      <?php

          namespace Database\Seeders;

          use App\Models\Product;
          use App\Models\Category;
          use Illuminate\Database\Seeder;
          use Illuminate\Database\Console\Seeds\WithoutModelEvents;

          class DataSeeder extends Seeder
          {
              public function run(): void
              {
                // Seed Categories
                $categories = [
                  'Electronics',
                  'Clothing & Fashion',
                  'Home & Garden',
                  'Sports & Outdoors',
                  'Books & Media',
                  'Health & Beauty'
                ];

                foreach ($categories as $category) {
                  Category::create([
                    'name' => $category,
                    'slug' => Str::slug($category),
                    'description' => "Description for {$category} category",
                    'status' => true,
                  ]);
                }

                // Seed Products
                Product::create([
                    'name' => 'Smartphone',
                    'slug' => 'smartphone',
                    'description' => 'A high-end smartphone.',
                    'unit_price' => 999.99,
                    'sale_price' => 899.99,
                    'image' => 'smartphone.jpg',
                    'status' => true,
                    'category_id' => 1,
                ]);

                Product::create([
                    'name' => 'T-Shirt',
                    'slug' => 't-shirt',
                    'description' => 'A comfortable cotton t-shirt.',
                    'unit_price' => 29.99,
                    'sale_price' => 24.99,
                    'image' => 't-shirt.jpg',
                    'status' => true,
                    'category_id' => 2,
                ]);
              }
          }
      ```

      Run the seeder:

      ```bash
      php artisan db:seed --class=DataSeeder
      ```

  **Checkpoint**: Check your database to ensure the `categories` and `products` tables are populated with sample data.

  ---

#### Step 3: Create Routes and Controller

  **Goal**: Define routes and a controller for CRUD operations.

  1. **Create the Product Controller**:

      ```bash
      php artisan make:controller ProductController --resource
      ```

      Create `app/Http/Controllers/ProductController.php` to handle CRUD operations with Inertia:

      ```php
      <?php

      namespace App\Http\Controllers;

      use App\Models\Product;
      use App\Models\Category;
      use Illuminate\Http\Request;
      use Inertia\Inertia;
      use Illuminate\Support\Str;
      use Illuminate\Support\Facades\Redirect;
      use Illuminate\Support\Facades\Storage;

      class ProductController extends Controller
      {
          public function index()
          {
              $products = Product::with('category')->paginate(10);
              return Inertia::render('Products/Index', [
                  'products' => $products,
              ]);
          }

          public function create()
          {
              $categories = Category::all();
              return Inertia::render('Products/Create', [
                  'categories' => $categories,
              ]);
          }

          public function store(Request $request)
          {
              $validated = $request->validate([
                  'name' => 'required|string|max:255',
                  'description' => 'nullable|string',
                  'unit_price' => 'required|numeric|min:0',
                  'sale_price' => 'nullable|numeric|min:0',
                  'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                  'status' => 'required|boolean',
                  'category_id' => 'required|exists:categories,id',
              ]);

              if ($request->hasFile('image')) {
                  $validated['image'] = $request->file('image')->store('products', 'public');
              }

              $validated['slug'] = Str::slug($request->name);
              Product::create($validated);

              return Redirect::route('products.index')->with('success', 'Product created successfully.');
          }

          public function show(Product $product)
          {
              $product->load('category');
              return Inertia::render('Products/Show', [
                  'product' => $product,
              ]);
          }

          public function edit(Product $product)
          {
              $categories = Category::all();
              return Inertia::render('Products/Edit', [
                  'product' => $product,
                  'categories' => $categories,
              ]);
          }

          public function update(Request $request, Product $product)
          {
              $validated = $request->validate([
                  'name' => 'required|string|max:255',
                  'description' => 'nullable|string',
                  'unit_price' => 'required|numeric|min:0',
                  'sale_price' => 'nullable|numeric|min:0',
                  'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                  'status' => 'required|boolean',
                  'category_id' => 'required|exists:categories,id',
              ]);

              if ($request->hasFile('image')) {
                  if ($product->image) {
                      Storage::disk('public')->delete($product->image);
                  }
                  $validated['image'] = $request->file('image')->store('products', 'public');
              } else {
                  $validated['image'] = $product->image;
              }

              $validated['slug'] = Str::slug($request->name);
              $product->update($validated);

              return Redirect::route('products.index')->with('success', 'Product updated successfully.');
          }

          public function destroy(Product $product)
          {
              if ($product->image) {
                  Storage::disk('public')->delete($product->image);
              }
              $product->delete();
              return Redirect::route('products.index')->with('success', 'Product deleted successfully.');
          }
      }
      ```

  2. **Define Routes**:
    Update `routes/web.php` to include resource routes for products:

      ```php
      <?php

      use App\Http\Controllers\ProductController;
      use Illuminate\Support\Facades\Route;

      Route::get('/', function () {
          return redirect()->route('products.index');
      });

      Route::resource('products', ProductController::class);
      ```

    **Checkpoint**: Test the routes by running `php artisan route:list` to ensure all CRUD routes (`index`, `create`, `store`, `edit`, `update`, `destroy`) are registered.
    ---
  3. **Link Storage**
    ```php

      php artisan storage:link

    ```

#### Step 4: Create Vue Components

  1. **Create MasterLayou component**
    **Goal**: Design a layout component with a navigation menu and a slot for child content.
    Create `resources/js/Layouts/MasterLayout.vue` with a Bootstrap 5 navbar and a main content area:

      ```html
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
      ```

    **Features**:
    - **Navbar**: Uses Bootstrap 5's `navbar` with a dark primary background (`bg-primary`). Includes a brand link to `/products` and a collapsible menu for mobile devices.
    - **Menu Links**: Links to `products.index` (Products) and `products.create` (Create Product) using Inertia's `Link` component.
    - **Active State**: Applies an `active` class to highlight the current page using `$page.url` for conditional styling.
    - **Main Content**: A `<slot>` renders child components within a Bootstrap `container` with `mt-4` for spacing.
    - **Scoped Styles**: Adds bold text for the brand and active links, with a subtle background for active nav items.

  ---

  **Goal**: Build Vue components for listing, creating, editing, and deleting products using the Composition API.

  2. **Create the Products Index Component**:
    Create `resources/js/Pages/Products/Index.vue` to display the product list with pagination:

      ```html
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
      ```

  3. **Create the Product Create Component**:
    Create `resources/js/Pages/Products/Create.vue` for adding new products:

      ```html
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
      ```

  4. **Create the Product Edit Component**:
    Create `resources/js/Pages/Products/Edit.vue` for updating products:

      ```html
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
      ```

  5. **Create the Product Show Component**:
      Create `resources/js/Pages/Products/Show.vue` for updating products:

        ```html
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
        ```
  
  6. **Create the ProductForm Component**:
      Create `resources/js/Components/ProductForm.vue` for updating products:

      ```html
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
        ```

    **Checkpoint**: Visit `http://localhost:8000/products` to see the product list with pagination. Click “Create Product” to test the create form, and try editing or deleting a product.  
    ---

#### Step 5: Add Flash Messages

**Goal**: Display success messages after CRUD operations.

1. **Update Inertia Middleware**:
   Update `app/Http/Middleware/HandleInertiaRequests.php` to share flash messages:

    ```php
    <?php

    namespace App\Http\Middleware;

    use Illuminate\Http\Request;
    use Inertia\Middleware;

    class HandleInertiaRequests extends Middleware
    {
        protected $rootView = 'app';

        public function share(Request $request): array
        {
          return [
              ...parent::share($request),
              'success' => fn () => $request->session()->get('success'),
              //
          ];
        }
    }
    ```

2. **Test Flash Messages**:
   The `Index.vue` component already displays success messages using `<div v-if="$page.props.success" class="alert alert-success">`. Create, update, or delete a product to see the success message.

---

#### Step 6: Testing and Debugging

**Goal**: Ensure the application works as expected.

1. **Test CRUD Operations**:

    - **Create**: Go to `/products/create`, fill out the form, and submit. Verify the product appears in the list.
    - **Read**: Check `/products` to see the paginated list with 10 products per page.
    - **Update**: Click “Edit” on a product, modify fields, and submit. Confirm the changes.
    - **Delete**: Click “Delete” on a product, confirm, and verify it’s removed.

2. **Test Pagination**:

    - Add more products via the seeder or create form to exceed 10 products.
    - Navigate through pagination links to ensure they work correctly.

3. **Debugging Tips**:
    - Use browser dev tools to inspect Vue components and network requests.
    - Check Laravel logs in `storage/logs/laravel.log` for server-side errors.
    - Ensure Ziggy routes are working by logging `route()` in the Vue console.

---

#### Step 7: Enhance the Application

**Goal**: Suggest improvements to deepen understanding.

1. **Add Image Upload**:

    - Modify the `ProductController` to handle file uploads and store images in `storage/app/public`.
    - Update the create/edit forms to include file inputs.

2. **Add Search and Filters**:

    - Add a search bar to filter products by name or category.
    - Implement filters for status or price range.

3. **Improve Styling**:
    - Customize Bootstrap styles or add custom CSS for a unique look.
    - Use Bootstrap modals for delete confirmations.

---

### Final Notes
  - **Review**: Revisit each step to ensure understanding. Explain the purpose of Inertia, Ziggy, and the Composition API to solidify concepts.
  - **Practice**: Encourage students to modify the application (e.g., add new fields, change styling) to reinforce learning.
  - **Resources**:
      -   [Inertia.js Documentation](https://inertiajs.com)
      -   [Laravel 12 Documentation](https://laravel.com/docs/12.x)
      -   [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
      -   [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/getting-started/introduction/)
      -   [Ziggy Documentation](https://github.com/tightenco/ziggy)

  **Final Checkpoint**: Deploy the application to a local server and demonstrate all CRUD operations and pagination to the class.
  ---
