<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.pages.categories.index');
    }

    public function getData(\Illuminate\Http\Request $request)
    {
        $query = Category::withCount('birds');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('image_url', fn ($c) => $c->image_url)
            ->addColumn('status_label', fn ($c) => $c->is_active ? 'Active' : 'Inactive')
            ->addColumn('actions', fn ($c) => [
                'showUrl'   => route('admin.categories.show', $c),
                'editUrl'   => route('admin.categories.edit', $c),
                'deleteUrl' => route('admin.categories.destroy', $c),
            ])
            ->make(true);
    }

    public function create()
    {
        return view('admin.pages.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $file  = $request->file('image');
            $image = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/categories'), $image);
        }

        Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
            'image'       => $image,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->loadCount('birds');
        $category->load(['birds' => fn ($q) => $q->latest()->take(6)]);
        return view('admin.pages.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = $category->image;
        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path('uploads/images/categories/' . $category->image))) {
                unlink(public_path('uploads/images/categories/' . $category->image));
            }
            $file  = $request->file('image');
            $image = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/categories'), $image);
        }

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
            'image'       => $image,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->birds()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete a category that has birds assigned to it.');
        }

        if ($category->image && file_exists(public_path('uploads/images/categories/' . $category->image))) {
            unlink(public_path('uploads/images/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
