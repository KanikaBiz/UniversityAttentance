<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = \App\Models\Category::all();
        // // dd($categories);
        // return view('categories.index', compact('categories'));
        $categories = \App\Models\Category::paginate(10);
        // dd($categories);
        return inertia('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('categories.create');
        return inertia('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        $validated['slug'] = Str::slug($validated['name'], '-');

        $category = \App\Models\Category::create($validated);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        return Redirect::route('categories.index')->with('success', 'Category created successfully.');

    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return inertia('Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return inertia('Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));

        return inertia('Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();

        return inertia('Categories/Index', [
            'categories' => \App\Models\Category::paginate(10),
        ]);
    }
}
