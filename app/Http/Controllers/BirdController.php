<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BirdController extends Controller
{
    public function index(Request $request)
    {
        $query = Bird::with('category:id,name,slug')
            ->select('id', 'name', 'slug', 'subtitle', 'price', 'original_price', 'image', 'badge', 'stock', 'color', 'wingspan_cm', 'featured', 'category_id', 'created_at')
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('species', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        // Category
        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->category);
        }

        // Color
        if ($request->filled('color')) {
            $query->whereIn('color', (array) $request->color);
        }

        // Price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        // Wingspan
        if ($request->filled('wingspan')) {
            match ($request->wingspan) {
                'compact'  => $query->where('wingspan_cm', '<', 30),
                'moderate' => $query->whereBetween('wingspan_cm', [30, 60]),
                'grand'    => $query->whereBetween('wingspan_cm', [61, 100]),
                'majestic' => $query->where('wingspan_cm', '>', 100),
                default    => null,
            };
        }

        // Sort
        match ($request->input('sort', 'featured')) {
            'price_high' => $query->orderByDesc('price'),
            'price_low'  => $query->orderBy('price'),
            'newest'     => $query->latest(),
            default      => $query->orderByDesc('featured')->orderByDesc('created_at'),
        };

        $birds = $query->paginate(12)->withQueryString();

        // AJAX — return only the grid partial
        if ($request->ajax()) {
            return view('pages._shop_grid', compact('birds'));
        }

        $categories = Cache::remember('shop.categories', 600, fn () =>
            Category::where('is_active', true)->select('id', 'name', 'slug')->orderBy('name')->get()
        );

        $colors = Cache::remember('shop.colors', 600, fn () =>
            Bird::where('is_active', true)->whereNotNull('color')
                ->distinct()->orderBy('color')->pluck('color')
        );

        $priceRange = Cache::remember('shop.price_range', 600, fn () => [
            'min' => (int) Bird::where('is_active', true)->min('price'),
            'max' => (int) Bird::where('is_active', true)->max('price'),
        ]);

        return view('pages.shop', compact('birds', 'categories', 'colors', 'priceRange'));
    }

    public function show(string $slug): View
    {
        $bird = Bird::with(['category:id,name,slug', 'reviews' => function ($q) {
                $q->with('user:id,name,avatar')->where('approved', true)->latest();
            }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedBirds = Bird::where('category_id', $bird->category_id)
            ->where('id', '!=', $bird->id)
            ->where('is_active', true)
            ->select('id', 'name', 'slug', 'subtitle', 'price', 'original_price', 'image', 'badge', 'stock', 'category_id')
            ->limit(3)
            ->get();

        // Resolve from already-loaded reviews collection — no extra query
        $userReview = auth()->check()
            ? $bird->reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('pages.product', compact('bird', 'relatedBirds', 'userReview'));
    }

    public function downloadGuide(string $slug)
    {
        $bird = Bird::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.bird-guide', compact('bird'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($bird->slug . '-care-guide.pdf');
    }
}
