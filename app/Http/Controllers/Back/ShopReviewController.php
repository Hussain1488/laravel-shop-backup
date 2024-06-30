<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ShopServiceReviewModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopReviewController extends Controller
{
    public function index()
    {
        $reviews = ShopServiceReviewModel::where('shop_id', Auth::user()->store->id)->filter()->shop()->paginate(20);
        return view('back.cooperationsales.reviews', compact('reviews'));
    }

    public function show($id)
    {

        $review = ShopServiceReviewModel::with('point')->findOrFail($id);
        return view('back.cooperationsales.shopreview', compact('review'))->render();
    }

    public function delete($id)
    {
        ShopServiceReviewModel::findOrFail($id)->delete();
        return response('success');
    }

    public function update(request $request, $review)
    {
        // dd($request);
        $review = ShopServiceReviewModel::findOrFail($review);
        $data = $this->validate($request, [
            'title'       => 'required|string',
            'body'        => 'required|string|max:1000',
            'rating'      => 'required|between:1,5',
            'suggest'     => 'nullable|in:yes,no,not_sure',
            'status'      => 'in:pending,accepted,rejected',
            'comment'     => 'in:valid,not_valid'
        ]);
        // dd($review);

        if ($request->default_rating) {
            $review->shop->default_rating = $request->default_rating;
        }

        $review->update($data);

        $review->point()->delete();

        $request->validate([
            'review.advantages.*' => 'string',
            'review.disadvantages.*' => 'string',
        ]);
        if ($request->user()->can('comments')) {
            $user = User::find($review->user_id);
            $user->comment_permision = $request->comment;
            $user->save();
        }

        $advantages = $request->input('review.advantages');

        if ($advantages) {
            foreach ($advantages as $advantage) {
                $review->point()->create([
                    'text' => $advantage,
                    'type' => 'positive',
                ]);
            }
        }

        $disadvantages = $request->input('review.disadvantages');

        if ($disadvantages) {
            foreach ($disadvantages as $advantage) {
                $review->point()->create([
                    'text' => $advantage,
                    'type' => 'negative',
                ]);
            }
        }

        if ($review->type == 'shop') {
            $review->shop->refreshRating();
        } else if ($review->type == 'employee') {
            $review->employee->refreshRating();
        } else if ($review->type == 'service') {
            $review->service->refreshRating();
        }

        return response($review);
    }
}
