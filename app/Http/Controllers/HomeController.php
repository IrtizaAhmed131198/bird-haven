<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Bird;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Featured birds — cache 10 min (changes only when admin edits a bird)
        $featuredBirds = Cache::remember('home.featured_birds', 600, fn () =>
            Bird::where('featured', true)
                ->where('is_active', true)
                ->with('category:id,name,slug')
                ->select('id', 'name', 'slug', 'subtitle', 'price', 'original_price', 'image', 'badge', 'stock', 'category_id', 'featured', 'created_at')
                ->latest()
                ->take(4)
                ->get()
        );

        $mobileFeatured = $featuredBirds->take(2);

        // Categories — cache 10 min
        $categories = Cache::remember('home.categories', 600, fn () =>
            Category::where('is_active', true)
                ->withCount('birds')
                ->orderByDesc('birds_count')
                ->take(6)
                ->get()
        );

        // Stats — single query, cache 5 min
        $stats = Cache::remember('home.stats', 300, function () {
            $row = Bird::where('is_active', true)
                ->selectRaw('COUNT(*) as birds, SUM(stock > 0) as in_stock')
                ->first();

            return [
                'birds'      => (int) $row->birds,
                'categories' => Category::where('is_active', true)->count(),
                'in_stock'   => (int) $row->in_stock,
            ];
        });

        // Hero settings — cache 1 hour (rarely changes)
        $hero = Cache::remember('home.hero', 3600, function () {
            $s = Setting::whereIn('key', ['hero_title', 'hero_subtitle', 'hero_image'])
                ->select('key', 'value')
                ->pluck('value', 'key');

            return [
                'title'    => $s->get('hero_title', 'Elevating the Art of Avian Companionship'),
                'subtitle' => $s->get('hero_subtitle', 'A curated sanctuary for the world\'s most magnificent birds and their guardians. Ethical, sustainable, and devoted to flight.'),
                'image'    => $s->get('hero_image')
                    ? asset('uploads/images/hero/' . $s->get('hero_image'))
                    : asset('assets/images/banner.png'),
            ];
        });

        // Featured accessories — cache 10 min
        $accessories = Cache::remember('home.accessories', 600, fn () =>
            Accessory::where('is_active', true)
                ->where('is_featured', true)
                ->select('id', 'name', 'slug', 'price', 'original_price', 'image', 'type')
                ->latest()
                ->take(3)
                ->get()
        );

        return view('pages.home', compact(
            'featuredBirds',
            'mobileFeatured',
            'categories',
            'stats',
            'accessories',
            'hero',
        ));
    }
}
