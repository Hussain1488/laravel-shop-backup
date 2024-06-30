<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\FaceSwapJob;
use App\Models\Gallery;
use App\Models\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class FaceSwapController extends Controller
{
    public function createJob(Request $request)
    {

        $user = Auth::user();
        $image = $request->image;
        if ($user->fittingCount && $user->fittingCount->count >= 3 && $user->fittingCount->date === now()->format('Y-m-d')) {
            // Log::info('true');
            return response()->json(['status' => false, 'message' => 'سهم روزانه پرو مجازی شما به اتمام رسیده است!']);
        } else {
            // Log::info('false');
            try {
                $swap_image = explode(',', $request->images);
                array_shift($swap_image);
                $targetImagePath = $request->target_image;
                $swapImagePath = public_path('uploads/tmp/') . $swap_image[0];

                // Log::info('Target Image Path: ' . $targetImagePath);
                // Log::info('Swap Image Path: ' . $swapImagePath);

                FaceSwapJob::dispatch($targetImagePath, $swapImagePath, $user, $image);

                return response()->json(['status' => true, 'message' => 'عکس پرو شما تا دقایقی دیگر آماده میشود و میتوانید در پروفایل خود مشاهده کنید!']);
                // return response()->json(['status' => 'Job has been dispatched.']);
            } catch (\Exception $e) {
                Log::error('Error creating job: ' . $e->getMessage());
                return response()->json(['error' => 'Error dispatching job'], 500);
            }
        }
    }
}
