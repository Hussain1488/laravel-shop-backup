<?php

namespace Themes\DefaultTheme\src\controllers;

use App\Http\Controllers\Controller;
use App\Models\DailyCodeModel;
use App\Models\InvoicesModel;
use App\Models\LotteryCodeModel;
use App\Models\LotteryWinnersModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LotteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //  going to installments records of user

    public function index()
    {
        $lottery = LotteryCodeModel::where('user_id', Auth::user()->id)->latest()->paginate(15, ['*'], 'lottery');
        $invoice = InvoicesModel::where('user_id', Auth::user()->id)->latest()->paginate(15, ['*'], 'invoice');
        $winners = LotteryWinnersModel::with(['user' => function ($query) {
            $query->get(['first_name', 'last_name', 'username', 'users.id as user_id']); // Include user_id
        }])
            ->latest()
            ->paginate(15, ['*'], 'winner');
        // dd($winners);
        return view('front::user.lottery.index', compact('lottery', 'invoice', 'winners'));
    }

    public function dailyCode(Request $request)
    {
        $code = DailyCodeModel::where($request->code_source, $request->daily_code)->first();
        if (!$code) {
            return response()->json(['status' => 'error', 'data' => 'کد وارد شده یا منبع انتخاب شده برای کد معتبر نمیباشد!']);
        }
        if (LotteryCodeModel::where('daily_code', $request->daily_code)->where('user_id', Auth::user()->id)->exists()) {

            return response()->json(['status' => 'error', 'data' => 'این کد یکبار توسط شما استفاده شده و دوباره نمیتوانید با این کد درخواست کد قرعه کشی بدهید!']);
        }
        if (Carbon::now()->format('Y-m-d') !== $code->date) {

            return response()->json(['status' => 'error', 'data' => 'کد وارد شده برای امروز نمیباشد!']);
        } else {
            // ['user_id', 'code', 'daily_code', 'invoice_id', 'weekly_state', 'monthly_state', 'state'];
            $code = $this->codeGenarator();
            $lotteryCode = LotteryCodeModel::create([
                'user_id' => Auth::user()->id,
                'code' => $code,
                'daily_code' => $request->daily_code,
                'weekly_state' => false,
                'monthly_state' => false,
                'state' => 'active',
            ]);
        }
        return response()->json(['status' => 'success', 'data' => $code]);
    }
    public function codeGenarator()
    {
        $code = rand(100000, 999999);
        if (LotteryCodeModel::where('code', $code)->exists()) {
            $this->codeGenarator();
        }

        return $code;
    }

    public function invoiceCode(Request $request)
    {
        // Validate the request data


        // Check if the invoice amount exceeds the maximum allowed value
        $amount = str_replace(',', '', $request->amount);
        $numeric_amount = (float) $amount;
        if ($numeric_amount > 999999999) {
            return response()->json(['state' => 'error', 'message' => 'مبلغ وارده بیشتر از حد مجاز میباشد، لطفا اصلاح نموده دوباره بفرستید!']);
        }

        // Check if the file has been successfully uploaded
        if (!$request->hasFile('invoice_img')) {
            return response()->json(['state' => 'error', 'message' => 'لطفا تصویر فاکتور را بارگذاری کنید.']);
        }

        // Move the uploaded file to the desired location
        $file = $request->file('invoice_img');
        $imageName = time() . '_invoice.' . $file->getClientOriginalExtension();
        $file->move('document/invoice_img/', $imageName);
        $image_path = '/document/invoice_img/' . $imageName;

        // Check if the invoice number already exists
        $codeExists = InvoicesModel::where('number', $request->number)->exists();
        if (!$codeExists) {
            // Create a new invoice record
            $newInvoice = InvoicesModel::create([
                'user_id' => Auth::user()->id,
                'number' => $request->number,
                'image' => $image_path,
                'state' => 'pending',
                'amount' => $numeric_amount
            ]);
            return response()->json(['state' => 'success', 'message' => 'اطلاعات فاکتور شما با موفقیت ثبت شد، بعد از تأیید کد قرعه کشی برای شما ایجاد میشود!']);
        } else {
            return response()->json(['state' => 'error', 'message' => 'شماره فاکتور وارد شده قبلا استفاده شده است!']);
        }
    }
}
