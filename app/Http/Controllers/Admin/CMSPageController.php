<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CMSPageController extends Controller
{
    public function index()
    {
        return view('admin.pages.cms.index');
    }

    public function getData(Request $request)
    {
        $query = CmsPage::select('cms_pages.*');

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        return DataTables::eloquent($query)
            ->addColumn('updated_fmt', fn ($p) => $p->updated_at->format('d M Y'))
            ->addColumn('actions', fn ($p) => [
                'editUrl'    => route('admin.cms.pages.edit', $p),
                'destroyUrl' => route('admin.cms.pages.destroy', $p),
            ])
            ->make(true);
    }

    public function create()
    {
        return view('admin.pages.cms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:cms_pages,slug',
            'content'          => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'is_published'     => 'boolean',
        ]);

        CmsPage::create([
            'title'            => $request->title,
            'slug'             => Str::slug($request->slug),
            'content'          => $request->content ?? '',
            'meta_description' => $request->meta_description,
            'is_published'     => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function show(CmsPage $page)
    {
        return redirect()->route('admin.cms.pages.edit', $page);
    }

    public function edit(CmsPage $page)
    {
        if ($page->slug === 'about') {
            return view('admin.pages.cms.edit-about', compact('page'));
        }

        return view('admin.pages.cms.edit', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        if ($page->slug === 'about') {
            return $this->updateAbout($request, $page);
        }

        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:cms_pages,slug,' . $page->id,
            'content'          => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'is_published'     => 'boolean',
        ]);

        $page->update([
            'title'            => $request->title,
            'slug'             => Str::slug($request->slug),
            'content'          => $request->content ?? '',
            'meta_description' => $request->meta_description,
            'is_published'     => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(CmsPage $page)
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    // ── About page ───────────────────────────────────────────────────────────

    private function updateAbout(Request $request, CmsPage $page): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'hero_headline'        => 'nullable|string|max:255',
            'hero_intro'           => 'nullable|string',
            'mobile_hero_headline' => 'nullable|string|max:255',
            'mobile_hero_intro'    => 'nullable|string',
            'story_title'          => 'nullable|string|max:255',
            'story_body_1'         => 'nullable|string',
            'story_body_2'         => 'nullable|string',
            'story_banner'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'story_banner_child'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mandate_quote'        => 'nullable|string',
            'mandate_stats'        => 'nullable|array',
            'mandate_stats.*.value' => 'nullable|string|max:20',
            'mandate_stats.*.label' => 'nullable|string|max:60',
            'cta_title'            => 'nullable|string|max:255',
            'cta_subtitle'         => 'nullable|string',
            'team'                 => 'nullable|array',
            'team.*.name'          => 'nullable|string|max:100',
            'team.*.role'          => 'nullable|string|max:100',
            'team.*.bio'           => 'nullable|string',
            'team.*.image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $meta    = $page->meta ?? [];
        $aboutDir = public_path('uploads/images/about');
        $teamDir  = public_path('uploads/images/team');

        foreach ([$aboutDir, $teamDir] as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0755, true);
        }

        // Story banner
        if ($request->hasFile('story_banner')) {
            $this->deleteUpload($aboutDir, $meta['story_banner'] ?? null);
            $meta['story_banner'] = $this->uploadFile($request->file('story_banner'), $aboutDir);
        }

        // Story banner child
        if ($request->hasFile('story_banner_child')) {
            $this->deleteUpload($aboutDir, $meta['story_banner_child'] ?? null);
            $meta['story_banner_child'] = $this->uploadFile($request->file('story_banner_child'), $aboutDir);
        }

        // Team member images
        $team = $meta['team'] ?? [];
        foreach ($request->input('team', []) as $i => $member) {
            $team[$i]['name'] = $member['name'] ?? ($team[$i]['name'] ?? '');
            $team[$i]['role'] = $member['role'] ?? ($team[$i]['role'] ?? '');
            $team[$i]['bio']  = $member['bio']  ?? ($team[$i]['bio']  ?? '');

            if ($request->hasFile("team.{$i}.image")) {
                $this->deleteUpload($teamDir, $team[$i]['image'] ?? null);
                $team[$i]['image'] = $this->uploadFile($request->file("team.{$i}.image"), $teamDir);
            }
        }

        $meta = array_merge($meta, [
            'hero_headline'        => $request->input('hero_headline'),
            'hero_intro'           => $request->input('hero_intro'),
            'mobile_hero_headline' => $request->input('mobile_hero_headline'),
            'mobile_hero_intro'    => $request->input('mobile_hero_intro'),
            'story_title'          => $request->input('story_title'),
            'story_body_1'         => $request->input('story_body_1'),
            'story_body_2'         => $request->input('story_body_2'),
            'mandate_quote'        => $request->input('mandate_quote'),
            'mandate_stats'        => $request->input('mandate_stats', $meta['mandate_stats'] ?? []),
            'cta_title'            => $request->input('cta_title'),
            'cta_subtitle'         => $request->input('cta_subtitle'),
            'team'                 => $team,
        ]);

        $page->update([
            'meta_description' => $request->input('meta_description', $page->meta_description),
            'is_published'     => $request->boolean('is_published', $page->is_published),
            'meta'             => $meta,
        ]);

        return redirect()->route('admin.cms.pages.edit', $page)
            ->with('success', 'About page updated successfully.');
    }

    private function uploadFile(\Illuminate\Http\UploadedFile $file, string $dir): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $filename);
        return $filename;
    }

    private function deleteUpload(string $dir, ?string $filename): void
    {
        if ($filename && file_exists($dir . '/' . $filename)) {
            unlink($dir . '/' . $filename);
        }
    }
}
