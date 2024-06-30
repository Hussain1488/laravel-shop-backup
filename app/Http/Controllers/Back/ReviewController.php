<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\EmployeeModal;
use App\Models\Review;
use App\Models\ShopServiceReviewModel;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::filter()->paginate(20);

        return view('back.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        // dd($review);
        return view('back.reviews.show', compact('review'))->render();
    }

    public function destroy(Review $review)
    {
        $review->delete();

        $review->product->refreshRating();

        return response('success');
    }

    public function update(Review $review, Request $request)
    {
        $data = $this->validate($request, [
            'title'       => 'required|string',
            'body'        => 'required|string|max:1000',
            'rating'      => 'required|between:1,5',
            'suggest'     => 'nullable|in:yes,no,not_sure',
            'status'      => 'in:pending,accepted,rejected',
            'comment'     => 'in:valid,not_valid'
        ]);
        // dd($review);

        $review->update($data);

        $review->points()->delete();

        $request->validate([
            'review.advantages.*' => 'string',
            'review.disadvantages.*' => 'string',
        ]);

        $user = User::find($review->user_id);
        $user->comment_permision = $request->comment;
        $user->save();

        $advantages = $request->input('review.advantages');

        if ($advantages) {
            foreach ($advantages as $advantage) {
                $review->points()->create([
                    'text' => $advantage,
                    'type' => 'positive',
                ]);
            }
        }

        $disadvantages = $request->input('review.disadvantages');

        if ($disadvantages) {
            foreach ($disadvantages as $advantage) {
                $review->points()->create([
                    'text' => $advantage,
                    'type' => 'negative',
                ]);
            }
        }

        $review->product->refreshRating();

        return response($review);
    }
    public function shop()
    {
        $this->authorize('comments');
        $reviews = ShopServiceReviewModel::filter()->shop()->paginate(20);

        return view('back.reviews.shop', compact('reviews'));
    }
    public function employee()
    {
        $this->authorize('comments');
        $reviews = ShopServiceReviewModel::filter()->employee()->paginate(20);
        // dd($reviews);
        return view('back.reviews.employee', compact('reviews'));
    }
    public function service()
    {
        $this->authorize('comments');
        $reviews = ShopServiceReviewModel::filter()->service()->paginate(20);
        // dd($reviews);

        return view('back.reviews.service', compact('reviews'));
    }
}
