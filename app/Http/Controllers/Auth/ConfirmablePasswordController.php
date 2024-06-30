<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('back.auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = User::find(Auth::user()->id);
        if ($request->flag == 'pass') {
            if (!Auth::guard('web')->validate([
                'username' => $request->user()->username,
                'password' => $request->password,
            ])) {
                throw ValidationException::withMessages([
                    'password' => 'گذرواژه وارد شده اشتباه است.',
                ]);
            }
            $request->session()->put('auth.password_confirmed_at', time());
        } else {
            $code = OneTimeCode::where('user_id', $user->id)
                ->where('code', $request->smscode)
                ->first();
            // $code1 = intval(str_replace(',', '', $code));
            if (!$code) {
                throw ValidationException::withMessages([
                    'password' => 'کد وارد شده اشتباه است.',
                ]);
            }
            $request->session()->put('auth.password_confirmed_at', time());
        }


        return response('success');
    }
    public function sendSms(Request $request)
    {
        PasswordResetLinkController::sendCode(User::find(Auth::user()->id));

        return response('success');
    }
}
