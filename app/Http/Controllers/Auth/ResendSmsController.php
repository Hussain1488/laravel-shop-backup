<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeCode;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResendSmsController extends Controller
{
    public function resendSmsRegistred(Request $request)
    {
        $user = User::where('username', $request->user)->first();
        PasswordResetLinkController::sendCode($user);


        return redirect()->back()->with('success', 'کد ورود مجدد برای شما ارسال شد.');
    }
    public function resendSmsRegistre(Request $request)
    {
        $user = new User();
        $user->username = Session::get('newUser')['number'];

        $type = new \stdClass(); // Creating a new instance of stdClass
        $type->code = rand(11111, 99999);
        $type->text = Sms::TYPES['RESEND_VERIFY_CODE'];

        // Assuming verifySms is a function that expects $type and $user as arguments
        verifySms($type, $user);

        Session::put('newUser', ['number' => Session::get('newUser')['number'], 'code' => $type->code]);

        return response()->json(['status' => 'success', 'data' => 'true']);
    }
}
