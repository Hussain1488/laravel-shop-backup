<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\Referral;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OneTimeLoginController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,username'
        ]);

        $view = config('front.pages.one-time-login');

        if (!$view || $validator->fails()) {
            abort(404);
        }

        $user = User::where('username', $request->mobile)->first();
        $verify_code = OneTimeCode::where('user_id', $user->id)->latest()->first();

        if (!$verify_code) {
            return redirect()->route('password.request');
        }

        $resend_time = $verify_code->created_at->addSeconds(60)->timestamp;

        return view($view, compact('resend_time', 'user'));
    }

    public function store(Request $request)
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

        $user->update([
            'force_to_password_change' => true,
        ]);

        Auth::loginUsingId($user->id, true);
        OneTimeCode::where('user_id', $user->id)->delete();

        return response('success');
    }
    public function codeRegister(Request $request)
    {
        if (optional(Session::get('newUser'))['code'] == $request->verify_code) {
            $user = User::create([
                'first_name' => 'بی نام',
                'last_name' => 'بی نام',
                'password' => 'NoN',
                'referral_code' => Referral::generateCode(),
                'username' => optional(Session::get('newUser'))['number'],
                'level' => 'user',
            ]);
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->balance = 0;
            $wallet->save();
            event(new Registered($user));
            event(new UserCreated($user));
            Auth::loginUsingId($user->id, true);
            return response()->json(['status' => 'success', 'data' => 'true']);
        } else {
            return response()->json(['status' => 'success', 'data' => 'false']);
        }
    }
}
