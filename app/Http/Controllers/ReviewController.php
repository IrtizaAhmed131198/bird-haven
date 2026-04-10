<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Bird $bird)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $alreadyReviewed = Review::where('bird_id', $bird->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('review_error', 'You have already submitted a review for this bird.');
        }

        Review::create([
            'bird_id' => $bird->id,
            'user_id' => $request->user()->id,
            'rating'  => $request->rating,
            'body'    => $request->body,
            'approved' => false,
        ]);

        return back()->with('review_success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }
}
