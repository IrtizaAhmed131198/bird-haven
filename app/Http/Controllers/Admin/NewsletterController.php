<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsletterController extends Controller
{
    public function index()
    {
        $totalCount  = NewsletterSubscriber::count();
        $activeCount = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.pages.newsletter.index', compact('totalCount', 'activeCount'));
    }

    public function getData(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('status',     fn ($s) => $s->is_active ? 'active' : 'unsubscribed')
            ->addColumn('date_fmt',   fn ($s) => $s->created_at->format('M d, Y'))
            ->addColumn('actions', function ($s) {
                $toggleUrl = route('admin.newsletter.toggle', $s->id);
                $deleteUrl = route('admin.newsletter.destroy', $s->id);
                $label     = $s->is_active ? 'Unsubscribe' : 'Re-subscribe';
                $icon      = $s->is_active ? 'person_remove' : 'person_add';
                return "
                    <div class='flex items-center gap-2'>
                        <form method='POST' action='{$toggleUrl}'>
                            <input type='hidden' name='_token' value='" . csrf_token() . "'>
                            <input type='hidden' name='_method' value='PATCH'>
                            <button type='submit' title='{$label}'
                                class='p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-primary transition-colors'>
                                <span class='material-symbols-outlined text-lg'>{$icon}</span>
                            </button>
                        </form>
                        <button onclick=\"confirmDelete('{$deleteUrl}')\"
                            class='p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors'>
                            <span class='material-symbols-outlined text-lg'>delete</span>
                        </button>
                    </div>
                ";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function toggle(NewsletterSubscriber $newsletter)
    {
        $newsletter->update([
            'is_active'       => !$newsletter->is_active,
            'unsubscribed_at' => $newsletter->is_active ? now() : null,
        ]);

        return back()->with('success', $newsletter->is_active ? 'Subscriber re-activated.' : 'Subscriber unsubscribed.');
    }

    public function destroy(NewsletterSubscriber $newsletter)
    {
        $newsletter->delete();

        return back()->with('success', 'Subscriber deleted.');
    }
}
