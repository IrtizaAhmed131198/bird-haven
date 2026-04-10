<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Accessory::where('is_active', true);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        match ($request->input('sort', 'featured')) {
            'price_high' => $query->orderByDesc('price'),
            'price_low'  => $query->orderBy('price'),
            'newest'     => $query->latest(),
            default      => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
        };

        $accessories = $query->paginate(12)->withQueryString();
        $types       = Accessory::types();

        return view('pages.accessories', compact('accessories', 'types'));
    }

    public function show(string $slug): View
    {
        $accessory = Accessory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Accessory::where('type', $accessory->type)
            ->where('id', '!=', $accessory->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('pages.accessory', compact('accessory', 'related'));
    }
}
