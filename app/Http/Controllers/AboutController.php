<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $page = CmsPage::where('slug', 'about')
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.about', compact('page'));
    }
}
