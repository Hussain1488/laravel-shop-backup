<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\createstore;
use App\Models\EmployeeModal;
use App\Models\ShopServiceModel;
use App\Models\ShopServiceReviewModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function index()
    {
        $shop = createstore::get();
        return view('front::shop.index', compact('shop'));
    }

    public function create()
    {
    }
    public function store()
    {
    }

    public function show($id)
    {
        $shop = createstore::with('gallery')->findOrFail($id);
        $reviews = ShopServiceReviewModel::where('shop_id', $id)->shop()->accepted()->with('user', 'point')->get();
        // dd($shop);
        return view('front::shop.show', compact('shop', 'reviews'));
    }



    public function storeReview(Request $request)
    {
        if (Auth::user()->comment_permision == 'not_valid') {
            return response('error');
        }

        try {
            $shop = createstore::findOrFail($request->shop_id);
            $data = $this->validate($request, [
                'title'       => 'required|string',
                'body'        => 'required|string|max:1000',
                'rating'      => 'required|between:1,5',
            ]);
            $data['user_id'] = Auth::user()->id; // Set user_id
            $data['type'] = 'shop'; // Set user_id
            $data['status'] = 'pending';
            $review = $shop->reviews()->updateOrCreate(
                [
                    'user_id' => Auth::user()->id
                ],
                $data
            );
            $review->point()->delete();
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

            return response('success');
        } catch (Exception $e) {
            Log::error($e);
            return response('error');
        }
    }


    public function employee($id)
    {

        $employee = EmployeeModal::with('shop.employee', 'shop.service')->findOrFail($id);
        $reviews = ShopServiceReviewModel::where('employee_id', $id)->employee()->accepted()->with('user', 'point')->get();
        // dd($employee);
        return view(
            'front::shop.services',
            compact('employee', 'reviews')
        );
    }
    public function service($id)
    {
        $service = ShopServiceModel::with('shop.employee', 'shop.service')->findOrFail($id);
        $reviews = ShopServiceReviewModel::where('service_id', $id)->service()->accepted()->with('user', 'point')->get();
        // dd($employee);
        return view(
            'front::shop.services',
            compact('service', 'reviews')
        );
    }

    public function employeeReview(Request $request)
    {
        if (Auth::user()->comment_permision == 'not_valid') {
            return response('error');
        }
        try {
            $employee = EmployeeModal::findOrFail($request->employee_id);
            $data = $this->validate($request, [
                'title'       => 'required|string',
                'body'        => 'required|string|max:1000',
                'rating'      => 'required|between:1,5',
            ]);
            $data['user_id'] = Auth::user()->id; // Set user_id
            $data['type'] = 'employee'; // Set user_id
            $data['status'] = 'pending';
            $data['shop_id'] = $employee->shop_id;
            $review = $employee->reviews()->updateOrCreate(
                [
                    'user_id' => Auth::user()->id
                ],
                $data
            );
            $review->point()->delete();
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
            $employee->refreshRating();
            return response('success');
        } catch (Exception $e) {
            Log::error($e);
            return response('error');
        }
    }

    public function serviceReview(Request $request)
    {
        if (Auth::user()->comment_permision == 'not_valid') {
            return response('error');
        }
        try {
            $service = ShopServiceModel::findOrFail($request->service_id);
            $data = $this->validate($request, [
                'title'       => 'required|string',
                'body'        => 'required|string|max:1000',
                'rating'      => 'required|between:1,5',
            ]);
            $data['user_id'] = Auth::user()->id; // Set user_id
            $data['type'] = 'service'; // Set user_id
            $data['status'] = 'pending';
            $data['shop_id'] = $service->shop_id;
            $review = $service->reviews()->updateOrCreate(
                [
                    'user_id' => Auth::user()->id
                ],
                $data
            );
            $review->point()->delete();
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
            $service->refreshRating();
            return response('success');
        } catch (Exception $e) {
            Log::error($e);
            return response('error');
        }
    }
    public function like($review)
    {
        $review = ShopServiceReviewModel::find($review);
        $review->likes()->updateOrCreate(
            [
                'user_id' => auth()->user()->id
            ],
            [
                'type' => 'like',
                'review_id' => $review
            ],
        );

        $review->refreshLikesCount();

        return response()->json(['review' => $review]);
    }

    public function dislike($review)
    {
        $review = ShopServiceReviewModel::find($review);
        $review->likes()->updateOrCreate(
            [
                'user_id' => auth()->user()->id
            ],
            [
                'type' => 'dislike'
            ],
        );

        $review->refreshLikesCount();

        return response()->json(['review' => $review]);
    }
}
