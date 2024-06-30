<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Models\installmentdetails;
use App\Models\Makeinstallmentsm;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\OrderPaid;
use App\Events\WalletAmountIncreased;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\buyertransaction;
use App\Models\createstore;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Models\User as ModelsUser;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Invoice;

class InstallmentsController extends Controller
{


    //  going to installments records of user
    public function index()
    {
        $perPage = 15;
        $installmentsm = Makeinstallmentsm::where('user_id', Auth::user()->id)->where('statususer', 0)->with('installments', 'store', 'user')->latest()->paginate($perPage, ['*'], 'insta');

        $installmentsm1 = installmentdetails::where('paymentstatus', 0)
            ->whereHas('installments', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->with(['installments' => function ($query) {
                $query->where('user_id', Auth::user()->id)->with('store', 'user');
            }])
            ->oldest()->orderBy('installmentnumber', 'asc')
            ->paginate($perPage, ['*'], 'insta1');

        $installmentsm2 = installmentdetails::where('paymentstatus', 1)
            ->whereHas('installments', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->with(['installments' => function ($query) {
                $query->where('user_id', Auth::user()->id)->with('store', 'user');
            }])->oldest()->orderBy('installmentnumber', 'asc')->paginate($perPage, ['*'], 'insta2');
        $userstat = 0;
        $paystatus = 0;
        // dd($installmentsm1);


        if ($installmentsm1->where('paymentstatus', 0)->count() > 0) {
            $userstat++;
        }


        if ($installmentsm2->where('paymentstatus', 1)->count() > 0) {
            $paystatus++;
        }

        $gateways = Gateway::whereHas('bank')->active()->get();
        $user = User::with('wallet')->find(Auth::user()->id);

        return view('front::user.installments.index', compact('installmentsm', 'installmentsm1', 'installmentsm2', 'user', 'userstat', 'paystatus', 'gateways'));
    }

    // paying the prepayment and creating the installments according its number.
    public function userStatus($id)
    {
        $installments = Makeinstallmentsm::find($id);
        $user = User::with('wallet')->find(Auth::user()->id);
        // dd($user);
        $wallet = $user->getWallet();

        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 24);
        })->first();
        if (!$bank_id) {
            return redirect()->back()->with('warning', 'درخواست شما با مشکل مواجه شده است، لطفا به مرکز گزارش بدهید!');
        }
        $bank_id1 = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 27);
        })->first();
        if (!$bank_id1) {
            return redirect()->back()->with('warning', 'درخواست شما با مشکل مواجه شده است، لطفا به مرکز گزارش بدهید!');
        }

        if ($wallet->balance >= $installments->prepaidamount && $user->purchasecredit >= $installments->prepaidamount) {
            try {
                DB::beginTransaction();
                $wallet->balance -= $installments->prepaidamount;
                $user->purchasecredit -= $installments->Creditamount;

                $Insta_dateils = new installmentdetails();

                $jalali_date_now = carbon::now();

                // $new_date = $jalali_date_now->addMonths(1)->format('Y-m-d');
                if ($installments->numberofinstallments > 0) {
                    if ($installments->typeofpayment == 'monthly_installment' || $installments->typeofpayment == 'm_none_profit') {

                        for ($i = 0; $i < $installments->numberofinstallments; $i++) {
                            $dutedate = $jalali_date_now->addMonths(1)->format('Y-m-d');
                            $Insta_dateils->create([
                                'state' => $i == 0 ? '1' : '0',
                                'installment_id' => $installments->id,
                                'installmentnumber' => $i + 1,
                                'installmentprice' => $installments->amounteachinstallment,
                                'paymentstatus' => 0,
                                'duedate' => $dutedate,
                            ]);
                        }
                    } else if ($installments->typeofpayment == 'weekly_installment' || $installments->typeofpayment == 'w_none_profit') {
                        for ($i = 0; $i < $installments->numberofinstallments; $i++) {
                            $dutedate = $jalali_date_now->addWeeks(1)->format('Y-m-d');
                            $Insta_dateils->create([
                                'state' => $i == 0 ? '1' : '0',
                                'installment_id' => $installments->id,
                                'installmentnumber' => $i + 1,
                                'installmentprice' => $installments->amounteachinstallment,
                                'paymentstatus' => 0,
                                'duedate' => $dutedate,
                            ]);
                        }
                    }
                    $message = 'پیش پرداخت شما با موفقیت پرداخت شده و اقساط شما ایجاد شد.';
                } else {

                    $message = 'پرداخت شما با موفقیت انجام شد.';
                }
                $history = $wallet->histories()->create([
                    'type'        => 'withdraw',
                    'amount'      => $installments->prepaidamount,
                    'description' => 'پرداخت پیش پرداخت',
                    'source'      => 'user',
                    'status'      => 'success'
                ]);

                $buyer_trans1 = buyertransaction::transaction(Auth::user(), $installments->prepaidamount, false, '1', '0', 'پرداخت پیش پرداخت');

                $buyer_trans = buyertransaction::transaction(Auth::user(), $installments->Creditamount, false, '0', '0', 'تأیید خرید');

                $bank1 = banktransaction::transaction($bank_id1->id, $installments->prepaidamount, true, $buyer_trans1->id, 'user');

                $bank = banktransaction::transaction($bank_id->id, ($installments->amounteachinstallment * $installments->numberofinstallments), false, null, 'user');


                $wallet->save(); // Save the changes to the database
                $user->save();
                $installments->statususer = 1;
                $installments->datepayment = carbon::now()->format('Y-m-d');
                $installments->save();
                DB::commit();
                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return redirect()->back()->with('warning', 'عملیات انجام نشد!');
            }
        } else {
            return redirect()->back()->with('warning', 'موجودی شما کمتر از مقدار پیش پرداخت است و یا اعتبار شما کمتر از مقدار خرید است، لطفا کیف پول خود را شارژ نموده دوباره تلاش کنید.');
        }
    }

    //  Paying specific installments and creating its bank transaction.
    public function paymentStatus($id, $st)
    {

        $recordCount = banktransaction::count();

        $bank = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 24);
        })->first();
        if ($bank) {
            $bank_id = $bank->id;
        } else {
            redirect()->back()->with('warning', 'درخواست شما با مشکل مواجه شده است،‌لطفا با مرکز تماس بگیرید!');
        }
        // dd($id, $st);
        $installments = Makeinstallmentsm::find($st);
        $insta_details = installmentdetails::where('installment_id', $st)
            ->where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        if ($insta_details) {
            $insta_details->update(['state' => 1]);
        }
        $insta_detail = installmentdetails::find($id);
        $insta_detail->paymentstatus = 1;
        $installments->paymentstatus = 1;

        $user = User::with('wallet')->find($installments->user_id);
        $wallet = $user->getWallet();

        if ($wallet->balance >= $insta_detail->installmentprice) {
            try {
                DB::beginTransaction();
                $wallet->balance -= $insta_detail->installmentprice;
                $history = $wallet->histories()->create([
                    'type'        => 'withdraw',
                    'amount'      => $insta_detail->installmentprice,
                    'description' => 'پرداخت قسط',
                    'source'      => 'user',
                    'status'      => 'success'
                ]);
                $buyer_trans = buyertransaction::transaction(Auth::user(), $insta_detail->installmentprice, false, '1', '0', 'پرداخت قسط');

                $bank = banktransaction::transaction($bank_id, $insta_detail->installmentprice, true, $buyer_trans->id, 'user');

                $wallet->save();
                $user->save();
                if ($insta_details) {

                    $insta_details->save();
                }
                $insta_detail->save();
                $installments->save();
                DB::commit();
                return redirect()->back()->with('success', 'قسط شما با موفقیت پرداخت شد!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('warning', 'عملیات انجام نشد!');
            }
        } else {
            return redirect()->back()->with('warning', 'موجودی کیف پول کافی نیست!');
        }
        // transaction($user, $amount, $status, $flag)

    }


    // Destroying specific installments
    public function refuse($id)
    {
        try {
            DB::beginTransaction();
            Makeinstallmentsm::refuse($id);
            DB::commit();
            return redirect()->back()->with('success', 'خرید شما با موفقیت حذف گردید');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('warning', 'مشکلی در انصراف خرید شما رخ داده است!');
        }
    }
    public function pay()
    {
        dd('this is pay');
    }
}
