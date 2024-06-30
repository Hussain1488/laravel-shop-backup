<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LoginWithCodeController extends Controller
{
    public function create(Request $request)
    {
        $view = config('front.pages.login-with-code');

        // if (!$view || option('login_with_code', 'off') == 'off') {
        //     abort(404);
        // }

        return view($view);
    }

    public function store(Request $request)
    {
        if (Session::get('captcha')) {
            $request->validate([
                'captcha' => ['required', 'captcha'],
            ], [
                'mobile.exists' => 'حساب کاربری با شماره موبایل ' . $request->mobile . ' وجود ندارد. لطفا ثبت نام کنید'
            ]);
        }
        Session::put('captcha', true);


        $type = new class
        {
            public $code;
            public $text;
        };

        $user = User::where('username', $request->mobile)->first();
        if ($user) {
            PasswordResetLinkController::sendCode($user);
            return response()->json(['status' => 'success', 'data' => 'login']);
        } else {
            // Create a new user
            $user = new User();
            $user->username = $request->mobile;
            $type = new \stdClass(); // Creating a new instance of stdClass
            $type->code = rand(11111, 99999);
            $type->text = Sms::TYPES['USER_CREATE'];
            // Assuming verifySms is a function that expects $type and $user as arguments
            $response = verifySms($type, $user);
            Session::put('newUser', ['number' => $request->mobile, 'code' => $type->code]);
            return response()->json(['status' => 'success', 'data' => 'true']);
        }
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'mobile' => 'required|exists:users,username',
        ]);

        $user = User::where('username', $request->mobile)->first();
        $time = Carbon::now()->subMinutes(15);
        $request->validate([
            'verify_code'     => [
                'required',
                Rule::exists('one_time_codes', 'code')->where(function ($query) use ($user, $time) {
                    $query->where('user_id', $user->id)->where('created_at', '>=', $time);
                }),
            ]
        ], [
            'verify_code.exists' => 'کد وارد شده اشتباه است'
        ]);

        Auth::loginUsingId($user->id, true);
        OneTimeCode::where('user_id', $user->id)->delete();

        return response('success');
    }
    public function refral(Request $request)
    {
        $refral_user = User::where('username', $request->refral_number)->first();
        if (!$refral_user) {
            return response()->json(['status' => 'fail', 'message' => 'شماره معرف شما یافت نشد، لطفا دوباره امتهان کنید!']);
        }
        // Log::info($request->refral_number);
        try {
            $user = User::find(Auth::user()->id);
            if ($user->referral_id) {
                return response()->json(['status' => 'fail', 'message' => 'شما قبلا معرف داشته اید، و برای بار دوم نمیتوانید از شماره معرف استفاده کنید!']);
            }
            if ($refral_user == $user) {
                return response()->json(['status' => 'fail', 'message' => 'شما نمیتوانید اط شماره خودتان به عنوان معرف استفاده کنید!']);
            } else if ($refral_user->wallet) {

                if ($refral_user && $user) {
                    $refral_user_wallet = $refral_user->wallet; // Access the wallet model associated with the referral user
                    $refral_user_wallet->balance += 200000;
                    $refral_user_wallet->save(); // Save the changes to the referral user's wallet balance

                    $referral_history = $refral_user_wallet->histories()->create([
                        'type'        => 'deposit',
                        'amount'      => 200000,
                        'description' => 'شارژ کیف پول، برای استفاده از شماره به عنوان معرف',
                        'source'      => 'user',
                        'status'      => 'success' // Assuming the recharge is successful
                    ]);

                    $user->referral_id = $refral_user->id;


                    $user_wallet = $user->wallet; // Access the wallet model associated with the current user
                    $user_wallet->balance += 200000;
                    $user_wallet->save(); // Save the changes to the current user's wallet balance
                    $user->save(); // Save the changes to the current user's wallet balance

                    $user_history = $user_wallet->histories()->create([
                        'type'        => 'deposit',
                        'amount'      => 200000,
                        'description' => 'شارژ کیف پول، برای استفاده از شماره معرف',
                        'source'      => 'user',
                        'status'      => 'success' // Assuming the recharge is successful
                    ]);
                }
            } else {
                return response()->json(['status' => 'fail', 'message' => 'شما نمیتوانید این کاربر را معرف داشته باشید!']);
            }
            return response()->json(['status' => 'success', 'message' => 'کد معرف پذیرفته شد و کیف پول شما و معرفتان هر یک ۲۰ هزار تومان شارژ شد!']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'fail', 'message' => 'عملیات با خطا روبرو شد']);
        }
    }
}
