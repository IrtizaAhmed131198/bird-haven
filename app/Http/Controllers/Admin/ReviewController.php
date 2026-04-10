<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    public function index()
    {
        $pendingCount = Review::where('approved', false)->count();

        return view('admin.pages.reviews.index', compact('pendingCount'));
    }

    public function getData(Request $request)
    {
        $query = Review::with('bird', 'user');

        if ($request->filled('status')) {
            $query->where('approved', $request->status === 'approved');
        }

        return DataTables::eloquent($query)
            ->addColumn('bird_name', fn ($r) => $r->bird?->name ?? '—')
            ->addColumn('user_name', fn ($r) => $r->user?->name ?? '—')
            ->addColumn('stars', fn ($r) => $r->rating)
            ->addColumn('excerpt', fn ($r) => \Str::limit(strip_tags($r->body), 80))
            ->addColumn('status', fn ($r) => $r->approved ? 'approved' : 'pending')
            ->addColumn('date_fmt', fn ($r) => $r->created_at->format('M d, Y'))
            ->addColumn('actions', function ($r) {
                $approveLabel = $r->approved ? 'Unapprove' : 'Approve';
                $approveIcon  = $r->approved ? 'unpublished' : 'check_circle';
                $approveUrl   = route('admin.reviews.approve', $r->id);
                $deleteUrl    = route('admin.reviews.destroy', $r->id);
                return "
                    <div class='flex items-center gap-2'>
                        <form method='POST' action='{$approveUrl}'>
                            <input type='hidden' name='_token' value='" . csrf_token() . "'>
                            <input type='hidden' name='_method' value='PATCH'>
                            <button type='submit' title='{$approveLabel}'
                                class='p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-emerald-600 transition-colors'>
                                <span class='material-symbols-outlined text-lg'>{$approveIcon}</span>
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

    public function approve(Review $review)
    {
        $review->update(['approved' => !$review->approved]);

        return back()->with('success', $review->approved ? 'Review approved.' : 'Review unapproved.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
