<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bird;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BirdController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        return view('admin.pages.birds.index', compact('categories'));
    }

    public function getData(Request $request)
    {
        $query = Bird::with('category')
            ->select(['birds.*']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'in_stock'  => $query->where('stock', '>', 1),
                'low_stock' => $query->where('stock', 1),
                'out'       => $query->where('stock', 0),
                default     => null,
            };
        }

        if ($request->filled('visibility')) {
            $query->where('is_active', $request->visibility === 'visible');
        }

        return DataTables::eloquent($query)
            ->addColumn('image_url', fn ($b) => $b->image_url)
            ->addColumn('category_name', fn ($b) => $b->category?->name ?? '—')
            ->addColumn('price_formatted', fn ($b) => '$' . number_format($b->price, 2))
            ->addColumn('stock_status', function ($b) {
                if ($b->stock === 0)  return ['label' => 'Out of Stock', 'level' => 'out'];
                if ($b->stock === 1)  return ['label' => 'Low Stock (1 unit)', 'level' => 'low'];
                return ['label' => 'In Stock (' . $b->stock . ' units)', 'level' => 'in'];
            })
            ->addColumn('actions', fn ($b) => [
                'showUrl'   => route('admin.birds.show', $b),
                'editUrl'   => route('admin.birds.edit', $b),
                'deleteUrl' => route('admin.birds.destroy', $b),
                'toggleUrl' => route('admin.birds.toggle', $b),
            ])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.birds.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'sku'            => 'nullable|string|max:50|unique:birds,sku',
            'category_id'    => 'required|exists:categories,id',
            'species'        => 'nullable|string|max:255',
            'subtitle'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'age'            => 'nullable|string|max:100',
            'temperament'    => 'nullable|string|max:255',
            'habitat'        => 'nullable|string',
            'habitat_guide'  => 'nullable|string',
            'nutrition'      => 'nullable|string',
            'nutrition_guide'=> 'nullable|string',
            'social'         => 'nullable|string',
            'social_guide'   => 'nullable|string',
            'wingspan_cm'    => 'nullable|integer|min:0',
            'color'          => 'nullable|string|max:100',
            'is_active'      => 'boolean',
            'featured'       => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['featured']  = $request->boolean('featured');
        $data['slug']      = Str::slug($request->name);

        $dir = public_path('uploads/images/birds');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($dir, $filename);
            $data['image'] = $filename;
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $i => $file) {
                $filename = time() . $i . '_' . $file->getClientOriginalName();
                $file->move($dir, $filename);
                $gallery[] = $filename;
            }
            $data['gallery'] = $gallery;
        }

        Bird::create($data);
        $this->bustBirdCaches();

        return redirect()->route('admin.birds.index')
            ->with('success', 'Bird added to inventory.');
    }

    public function show(Bird $bird)
    {
        $bird->load('category', 'reviews');
        return view('admin.pages.birds.show', compact('bird'));
    }

    public function edit(Bird $bird)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.birds.edit', compact('bird', 'categories'));
    }

    public function update(Request $request, Bird $bird)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'sku'            => 'nullable|string|max:50|unique:birds,sku,' . $bird->id,
            'category_id'    => 'required|exists:categories,id',
            'species'        => 'nullable|string|max:255',
            'subtitle'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'age'            => 'nullable|string|max:100',
            'temperament'    => 'nullable|string|max:255',
            'habitat'        => 'nullable|string',
            'habitat_guide'  => 'nullable|string',
            'nutrition'      => 'nullable|string',
            'nutrition_guide'=> 'nullable|string',
            'social'         => 'nullable|string',
            'social_guide'   => 'nullable|string',
            'wingspan_cm'    => 'nullable|integer|min:0',
            'color'          => 'nullable|string|max:100',
            'is_active'      => 'boolean',
            'featured'       => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_remove' => 'nullable|array',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['featured']  = $request->boolean('featured');

        $dir = public_path('uploads/images/birds');

        if ($request->hasFile('image')) {
            if ($bird->image && file_exists($dir . '/' . $bird->image)) {
                unlink($dir . '/' . $bird->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($dir, $filename);
            $data['image'] = $filename;
        }

        // Gallery: start from existing, remove deleted, append new uploads
        $gallery = $bird->gallery ?? [];

        foreach ($request->input('gallery_remove', []) as $remove) {
            $gallery = array_filter($gallery, fn ($g) => $g !== $remove);
            if (file_exists($dir . '/' . $remove)) {
                unlink($dir . '/' . $remove);
            }
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                $filename = time() . $i . '_' . $file->getClientOriginalName();
                $file->move($dir, $filename);
                $gallery[] = $filename;
            }
        }

        $data['gallery'] = array_values($gallery);

        $bird->update($data);
        $this->bustBirdCaches();

        return redirect()->route('admin.birds.index')
            ->with('success', 'Bird updated successfully.');
    }

    public function toggle(Bird $bird)
    {
        $bird->update(['is_active' => !$bird->is_active]);
        $this->bustBirdCaches();
        return response()->json(['is_active' => $bird->is_active]);
    }

    public function destroy(Bird $bird)
    {
        $dir = public_path('uploads/images/birds');

        if ($bird->image && file_exists($dir . '/' . $bird->image)) {
            unlink($dir . '/' . $bird->image);
        }

        foreach ($bird->gallery ?? [] as $g) {
            if (file_exists($dir . '/' . $g)) unlink($dir . '/' . $g);
        }

        $bird->delete();
        $this->bustBirdCaches();

        return redirect()->route('admin.birds.index')
            ->with('success', 'Bird removed from inventory.');
    }

    private function bustBirdCaches(): void
    {
        Cache::forget('home.featured_birds');
        Cache::forget('home.stats');
        Cache::forget('shop.colors');
        Cache::forget('shop.price_range');
        Cache::forget('shop.categories');
    }
}
