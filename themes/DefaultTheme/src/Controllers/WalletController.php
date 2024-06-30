<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Events\OrderPaid;
use App\Events\WalletAmountIncreased;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\buyertransaction;
use App\Models\Gateway;
use App\Models\OneTimeCode;
use App\Models\Sms;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Invoice;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    public function index()
    {
        $wallet    = auth()->user()->getWallet();
        $trans = $wallet->histories()->latest()->paginate(15);
        $user = User::with('wallet')->find(Auth::user()->id);
        $gateways = Gateway::whereHas('bank')->active()->get();

        return view('front::user.wallet.index', compact('trans', 'user', 'gateways'));
    }

    public function show(WalletHistory $wallet)
    {
        return view('front::user.wallet.show')->with('history', $wallet);
    }

    public function create()
    {
        $gateways = Gateway::active()->get();

        return view('front::user.wallet.create', compact('gateways'));
    }

    public function store(Request $request)
    {

        $bank_id = BankAccount::where('gateway_id', Gateway::where('key', $request->gateway)->first()->id)->first();
        // dd($bank_id);
        session()->put('bank_id', $bank_id);

        if (!session()->get('bank_id')) {
            return redirect()->back()->with('warning', 'خطای سرور. لطفا به مرکز اطلاع بدهید.');
        }

        $gateways = Gateway::active()->pluck('key')->toArray();
        $request->validate([
            'amount'      => 'required|numeric|max:5000000000|min:10000',
            'gateway'     => 'required|in:' . implode(',', $gateways),
        ]);
        // dd($request->gateway);
        $gateway = $request->gateway;
        $amount  = intval($request->amount / 10);
        $wallet  = auth()->user()->getWallet();

        // dd($gateway, $amount, $wallet);
        $history = $wallet->histories()->create([
            'type'        => 'deposit',
            'amount'      => $amount * 10,
            'description' =>  trans('front::messages.controller.wallet-recharge'),
            'source'      => 'user',
            'status'      => 'fail'
        ]);


        try {
            $gateway_configs = get_gateway_configs($gateway);
            return Payment::via($gateway)->config($gateway_configs)->callbackUrl(route('front.wallet.verify', ['gateway' => $gateway]))->purchase(
                (new Invoice)->amount($amount),
                function ($driver, $transactionId) use ($history, $gateway, $amount) {
                    DB::table('transactions')->insert([
                        'status'               => false,
                        'amount'               => $amount * 10,
                        'factorNumber'         => $history->id,
                        'mobile'               => auth()->user()->username,
                        'message'              => trans('front::messages.controller.created-for-gateway') . $gateway,
                        'transID'              => $transactionId,
                        'token'                => $transactionId,
                        'user_id'              => auth()->user()->id,
                        'transactionable_type' => WalletHistory::class,
                        'transactionable_id'   => $history->id,
                        'gateway_id'           => Gateway::where('key', $gateway)->first()->id,
                        "created_at"           => Carbon::now(),
                        "updated_at"           => Carbon::now(),
                    ]);
                    session()->put('transactionId', $transactionId);
                    session()->put('amount', $amount);
                }
            )->pay()->render();
        } catch (Exception $e) {
            return redirect()->route('front.wallet.index', ['history' => $history])->with('transaction-error', $e->getMessage());
        }
    }

    public function verify($gateway)
    {
        $transactionId = session()->get('transactionId');
        $amount        = session()->get('amount');
        $transaction = Transaction::where('status', false)->where('transID', $transactionId)->firstOrFail();

        $history = $transaction->transactionable;
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();

        $wallet->balance += intval($amount) * 10;

        $gateway_configs = get_gateway_configs($gateway);

        $receipt = Payment::via($gateway)->config($gateway_configs);

        try {
            $receipt = Payment::via($gateway)->config($gateway_configs);

            if ($amount) {
                $receipt = $receipt->amount(intval($amount));
            }

            // dd($history->wallet);
            $receipt = $receipt->transactionId($transactionId)->verify();

            DB::table('transactions')->where('transID', $transactionId)->update([
                'status'               => 1,
                'traceNumber'          => $receipt->getReferenceId(),
                'message'              => $transaction->message . '<br>' . trans('front::messages.controller.successful-gateway') . $gateway,
                'updated_at'           => Carbon::now(),
            ]);

            $history->update([
                'status' => 'success',
            ]);

            $wallet->save();
            event(new WalletAmountIncreased($history->wallet));
            $user = User::find(Auth::user()->id);

            $user_trans = buyertransaction::transaction($user, ($amount * 10), true, '1', '1', 'شارژ کیف پول');
            banktransaction::transaction(session()->get('bank_id')->id, ($amount * 10), false, $user_trans->id, 'user');


            if ($history->order) {
                $result = $history->order->payUsingWallet();

                if ($result) {
                    $history->order->update([
                        'status' => 'paid',
                    ]);

                    event(new OrderPaid($history->order));

                    return redirect()->route('front.orders.show', ['order' => $history->order])->with('message', 'ok');
                }
            }

            return redirect()->route('front.wallet.index', ['history' => $history])->with('message', 'ok');
        } catch (\Exception $exception) {
            // \Log::error('Exception: ' . $exception->getMessage());

            DB::table('transactions')->where('transID', $transactionId)->update([
                'message'              => $transaction->message . '<br>' . $exception->getMessage(),
                "updated_at"           => Carbon::now(),
            ]);

            if ($history->order) {
                return redirect()->route('front.orders.show', ['order' => $history->order])
                    ->with('transaction-error', $exception->getMessage())
                    ->with('order_id', $history->order->id);
            }

            return redirect()->route('front.wallet.index', ['history' => $history])->with('transaction-error', $exception->getMessage());
        }
    }

    public function codeGenerate($smsType)
    {
        $sms = oneTimeCode(Auth::user(), Sms::TYPES[$smsType]);
        verifySms($sms, Auth::user());
        return response('success');
    }
    public function sendCode(Request $request)
    {

        $user = Auth::user();

        $code = OneTimeCode::where('user_id', $user->id)
            ->where('code', $request->verify_code)
            ->first();

        if ($code) {
            return response('success');
        } else {
            return response()->json(['data' => 'کد وارد شده اشتباه است لطفا کد درست را وارد کنید.'], 422);
        }
    }

    public function rechargeVarify(Request $request)
    {

        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 21);
        })->first();
        if (!$bank_id) {
            return redirect()->back()->with('warning', 'خطای سرور. لطفا به مرکز اطلاع بدهید.');
        }
        $RecharAmount = intval(str_replace(',', '', $request->recharge_amount));




        $user = User::find($request->user_id);
        $user_trans = buyertransaction::transaction($user, $RecharAmount, false, '1', '1', 'شارژ کیف پول');

        banktransaction::transaction($bank_id->id, $RecharAmount, false, $user_trans->id, 'user');

        $user = User::find($request->user_id);
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet) {

            $wallet->balance += $RecharAmount;
            $wallet->save(); // Save the changes to the database
        } else {
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->balance = $RecharAmount;
            $wallet->save();
        };
        $user->save();
        return redirect()->back()->with('success', 'شارژ کیف پول برای شما با موفقیت انجام شد!');
    }
}
