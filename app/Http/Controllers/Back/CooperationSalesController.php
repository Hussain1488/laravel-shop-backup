<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\createstore;
use App\Models\Makeinstallmentsm;
use App\Models\Role;
use App\Models\StoreDoumentModel;
use App\Models\User;
use App\Models\CooperationSales;
use App\Models\createstoretransaction;
use App\Models\PaymentListModel;
use App\Models\StoreTransactionDetailsModel;
use Carbon\Carbon;
use CreateStoreTransactionDetailsTable;
use Exception;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Melipayamak\MelipayamakApi;
use Morilog\Jalali\Jalalian;
use ParagonIE\Sodium\Compat;

class CooperationSalesController extends Controller
{
    //Cooperation Sales index page
    public function index()
    {
        $this->authorize('cooperationsales.index');

        if (Auth::user()->store) {
            $perPage = 10;
            $installmentsm = Makeinstallmentsm::where('seller_id', Auth::user()->id)->where('statususer', 0)->with('store', 'user')->latest()->paginate($perPage, ['*'], 'insta');
            $installmentsm1 = Makeinstallmentsm::where('seller_id', Auth::user()->id)->where('statususer', 1)->where('status', 0)->with('store', 'user')->latest()->paginate($perPage, ['*'], 'insta1');
            $store = createstore::where('user_id', Auth::user()->id)->first();

            $today = Carbon::now();

            foreach ($installmentsm as $key) {
                if ($key->datepayment != null) {
                    $jalaliDate = Jalalian::fromFormat('Y-m-d', $key->datepayment);
                    $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $jalaliDate->toCarbon()->toDateTimeString());
                    $key->CarbonDate = $carbonDate;
                }
            }

            return view('back.cooperationsales.index', compact('installmentsm', 'installmentsm1', 'store', 'today'));
        } else {
            abort(403);
        }
    }

    //Creating new installments page
    public function create()
    {
        $this->authorize('cooperationsales.create');
        if (Auth::user()->store) {
            $shopkeeper = Auth::user();
            $shop = createstore::where('user_id', $shopkeeper->id)->first();
            $jalaliEndDate = Jalalian::fromFormat('Y-m-d', $shop->enddate);
            $carbonEndDate = Carbon::createFromFormat('Y-m-d H:i:s', $jalaliEndDate->toCarbon()->toDateTimeString());
            $shop->enddate = $carbonEndDate;

            $users = User::where('level', 'user')->get();

            return view('back.cooperationsales.create', compact('users', 'shop'));
        } else {
            abort(403);
        }
    }

    // storing new installments function
    public function store(Request $request)
    {
        // dd($request);

        $numberofinstallments = $request->numberofinstallments;
        $this->authorize('cooperationsales.create');
        $store = createstore::where('user_id', Auth::user()->id)->first();
        // Log::info($store);
        $user = User::find($request->user_id);
        $Creditamount = intval(str_replace(',', '', $request->Creditamount));

        if (!$store->storecredit >= $Creditamount) {
            toastr()->error('اعتبار فروشگاه برای این فروش کافی نیست');
            return redirect()->back();
        }
        if (!$user->purchasecredit >= $Creditamount) {
            toastr()->error('اعتبار کاربر برای این فروش کافی نیست!');
            return redirect()->back();
        }

        $prepaidamount = 0;
        $amounteachinstallment = 0;

        if ($request->typeofpayment == 'cash') {
            $prepaidamount = $Creditamount;
            $amounteachinstallment = 0;
        } else if ($request->typeofpayment == 'monthly_installment') {
            $total_pay = $Creditamount + $Creditamount * (($numberofinstallments * 3) / 100);
            $prepaidamount = $total_pay * 0.3;
            $rest_pay = $total_pay - $prepaidamount;
            $amounteachinstallment = intval($rest_pay / $numberofinstallments);
        } else if ($request->typeofpayment == 'weekly_installment') {
            $total_pay = $Creditamount + $Creditamount * (($numberofinstallments * 0.9) / 100);
            $prepaidamount = $total_pay * 0.3;
            $rest_pay = $total_pay - $prepaidamount;
            $amounteachinstallment = intval($rest_pay / $numberofinstallments);
        } else if ($request->typeofpayment == 'm_none_profit') {
            $prepaidamount = $Creditamount * 0.3;
            $rest_pay = $Creditamount - $prepaidamount;
            $amounteachinstallment = intval($rest_pay / 4);
            $numberofinstallments = 4;
        } else if ($request->typeofpayment == 'w_none_profit') {
            // $total_pay = $Creditamount + $Creditamount * (($request->numberofinstallments * 0.9) / 100);
            $prepaidamount = $Creditamount * 0.3;
            $rest_pay = $Creditamount - $prepaidamount;
            $amounteachinstallment = intval($rest_pay / 12);
            $numberofinstallments = 12;
        }
        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 25);
        })->first();
        if (!$bank_id) {
            toastr()->error('شما هیچ بانکی با ماهیت واسط اعتبار فروشگاه ها ندارید. لطفا ایجاد نموده دوباره تلاش کنید.');
            return redirect()->back();
        }

        $description = 'انجام فروش(ساخت قسط)';
        $trans_data = [
            'تراکنش:' => $description,
            'توسط:' => Auth::user()->username,
            'برای:' => User::find($request->user_id)->username,
            'مقدار تراکنش:' => number_format($Creditamount) . 'ریال',
            'نوع فروش:' => $request->typeofpayment == 'cash' ? 'نقدی' : 'اقساطی',
            'تعداد قسط:' => $request->typeofpayment == 'cash' ? 0 : $numberofinstallments,
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];

        try {
            DB::beginTransaction();
            $store->storecredit -= $Creditamount;

            Makeinstallmentsm::create([
                'status' => 0,
                'seller_id' => Auth::user()->id,
                'Creditamount' => $Creditamount,
                'user_id' => $request->user_id,
                'typeofpayment' => $request->typeofpayment,
                'numberofinstallments' => $request->typeofpayment == 'cash' ? 0 : $numberofinstallments,
                'prepaidamount' => $prepaidamount,
                'amounteachinstallment' => $amounteachinstallment,
                'buyerstatus' => 0,
                'paymentstatus' => 0,
                'statususer' => 0,
                'store_id' => $store->id,
            ]);
            $store_trans = createstoretransaction::storeTransaction($store, $Creditamount, false, 3, 0, $request->user_id, null, $description);
            StoreTransactionDetailsModel::createDetail($store_trans, $trans_data);
            // $bankt_tras = banktransaction::transaction($bank_id->id, $Creditamount, false, $store_trans, 'store');


            $store->save();
            toastr()->success('قسط کاربر با موفقیت ایجاد شد.');

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);
            toastr()->error('مشکلی در ایجاد قسط کاربر به وجود آمده است.' . $e);
        }
        return redirect()->back();
    }

    // going to income view page
    public function Income()
    {
        return view('back.cooperationsales.Income');
    }

    // going to clearing view page
    public function clearing()
    {
        $this->authorize('cooperationsales.clearing');
        if (Auth::user()->store) {
            $store = createstore::where('user_id', Auth::user()->id)->first();

            return view('back.cooperationsales.clearing', Compact('store'));
        } else {
            abort(403);
        }
    }
    public function clearingStore(Request $request)
    {

        $this->authorize('cooperationsales.clearing');
        $store = createstore::find($request->store);
        if (Auth::user()->id == $store->user_id) {
            $depositamount = str_replace(',', '', $request->depositamount);
            $docPath = '';
            if ($request->hasFile('factor')) {
                // dd($request->file('factor'));
                $files = $request->file('factor');
                $paths = [];
                foreach ($files as $file) {

                    $imageName = time() . '_clearing.' . $file->getClientOriginalExtension();
                    $file->move('document/ClearingDoc/', $imageName);
                    $path = '/document/ClearingDoc/' . $imageName;
                    $paths[] = $path;
                }
                $docPath = json_encode($paths);
            }

            $number = PaymentListModel::count();
            if ($number > 0 && $number != 0) {
                $number = PaymentListModel::latest()->first()->list_id  + 1;
                $final_price = PaymentListModel::latest()->first()->final_price  + $depositamount;
            } else {
                $final_price = $depositamount;
                $number = 10000;
            }
            // dd($final_price);
            $store->salesamount -= $depositamount;
            $register_number = $number;
            $description = 'درخواست واریز';
            $trans_data = [
                'تراکنش:' => $description,
                'توسط:' => Auth::user()->username,
                'مقدار درخواست:' => number_format($depositamount) . 'ریال',
                'تاریخ:' => Jalalian::now()->format('Y/m/d'),
                'زمان:' => Jalalian::now()->format('H:i:s'),
            ];
            // creating new payment request transaction.
            try {
                DB::beginTransaction();
                $transaction = createstoretransaction::storeTransaction($store, $depositamount, false, 0, 1, $user = null, $timestamp = null, $description);

                PaymentListModel::create([
                    'list_id' => $number,
                    'store_id' => $request->store,
                    'depositamount' => $depositamount,
                    'final_price' => $final_price,
                    'shabanumber' => $request->shabanumber,
                    'factor' => $docPath,
                    'depositdate' => carbon::now()->format('Y-m-d'),
                    'trans_id' => $transaction,
                ]);
                // creating new store transaction for mainWallet transaction.
                StoreTransactionDetailsModel::createDetail($transaction, $trans_data);
                // $bank_trans = banktransaction::transaction($bank_id->id, $depositamount, false, $transaction, 'store');
                $store->save();

                DB::commit();
                toastr()->success('درخواست تسویه حساب با موفقیت ارسال شد.');
                return redirect()->back()->with('register_number', $register_number);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                toastr()->error('خطایی در ارسال درخواست رخ داده است!.' . $e);
            }
            return redirect()->back();
        } else {
            toastr()->error('اجازه دسترسی ندارید!');
            return redirect()->back();
        }
    }

    // changing the status of installments status to paid.
    public function changeStatus($id)
    {
        $newUpdate = Makeinstallmentsm::find($id);
        $newUpdate->status = 1;
        $newUpdate->save();
        return redirect()->back();
    }
    public function PayRequest($id, $id2)
    {

        // dd()
        $store = createstore::find($id);
        if (Auth::user()->id == $store->user_id) {

            $bank_id =   BankAccount::whereHas('account_type', function ($query) {
                $query->where('code', 24);
            })->first();

            if (!$bank_id) {
                toastr()->error('شما هیچ بانکی با ماهیت واسط اقساط ندارید. لطفا ایجاد نموده دوباره تلاش کنید.');
                return redirect()->back();
            }
            $description = 'درخواست تسویه';
            // dd($store);
            $installment = Makeinstallmentsm::find($id2);
            if ($installment->typeofpayment == 'w_none_profit' || $installment->typeofpayment == 'm_none_profit') {
                $saleType = 'فروش اقساطی بدون کارمزد';
                $fee = $store->no_profit_fee;
            } elseif ($installment->typeofpayment == 'cash') {
                $saleType = 'فروش نقدی';
                $fee = $store->feepercentage;
            } else {
                $saleType = 'فروش اقساطی';
                $fee = $store->feepercentage;
            }
            if ($installment->status == 1) {
                toastr()->warning('شما نمیتوانید برای این فروش درخواست تسویه کنید! قبلا درخواست شده است.');
                return redirect()->back();
            }
            $result = $installment->Creditamount * $fee / 100;
            $final = $installment->Creditamount - $result;
            $trans_data = [
                'تراکنش:'                         => $description,
                'نوع فروش: '                      => $saleType,
                'توسط:'                           => Auth::user()->username,
                'مبلغ فروش بعد از کسر کارمزد:'   => number_format($final) . 'ریال',
                'کارمزد:'                         => number_format($result) . 'ریال',
                'تاریخ:'                           => Jalalian::now()->format('Y/m/d'),
                'زمان:'                           => Jalalian::now()->format('H:i:s'),
            ];

            try {

                DB::beginTransaction();
                $store->salesamount += $final;
                $installment->status = 1;

                $number = createstoretransaction::count();

                if ($number > 0 && $number != 0) {
                    $number = createstoretransaction::latest()->first()->documentnumber  + 1;
                    // $final_price = createstoretransaction::latest()->first()->finalprice;
                } else {
                    $number = 10000;
                    // $final_price =
                }
                $transaction = createstoretransaction::storeTransaction($store, $installment->Creditamount, true, 1, 1, $installment->user_id, $installment->updated_at, $description);
                StoreTransactionDetailsModel::createDetail($transaction, $trans_data);
                $bank_trans = banktransaction::transaction($store->account_id, $result, true, $transaction, 'store');

                // $bank_trans = banktransaction::transaction($bank_id->id, $installment->Creditamount, false, $transaction, 'store');

                $installment->save();
                $store->save();
                DB::commit();
                toastr()->success('درخواست تسویه حساب موفقیت آمیز انجام شد.');
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                toastr()->error('مشکلی در درخواست تسویه حساب رخ داده است!!' . $e);
            }
            return redirect()->route('admin.cooperationsales.index');
        } else {
            abort(403);
        }
    }
    public function mainWallet($id)
    {
        $this->authorize('cooperationsales.mainWallet');
        // dd(Auth::user()->store()->id);
        $shop = createstore::findOrFail($id);
        if ($shop->user_id == Auth::user()->id || Auth::user()->level == 'creator') {
            $trans = createstoretransaction::where('flag', 1)->where('store_id', $id)->latest()->paginate(20);
            // dd(count($trans));
            if (count($trans) > 0) {
                $total = createstoretransaction::where('flag', 1)->where('store_id', $id)->latest()->first()->finalprice;
            } else {
                $total = 0;
            }
            $store = createstore::find($id);
            return view('back.cooperationsales.mainwallet', compact('trans', 'store', 'total'));
        } else {
            toastr()->warning('اجازه دسترسی ندارید!');
            return redirect()->back();
        }
    }
    public function payRequestWallet($id)
    {
        $this->authorize('cooperationsales.payRequestWallet');
        $trans = PaymentListModel::where('store_id', $id)->latest()->paginate(20);
        $store = createstore::find($id);
        if (count($trans) > 0) {
            $total = PaymentListModel::where('store_id', $id)->latest()->first()->final_price;
        } else {
            $total = 0;
        }
        return view('back.cooperationsales.pay_trans', compact('trans', 'store', 'total'));
    }
    public function paidSales($id)
    {

        $this->authorize('cooperationsales.paidSales');

        $trans =
            PaymentListModel::where('store_id', $id)->where('status', 1)->latest()->paginate(20);
        $store = createstore::find($id);
        if (count($trans) > 0) {
            $total = PaymentListModel::where('store_id', $id)->where('status', 1)->latest()->latest()->first()->finalprice;
        } else {
            $total = 0;
        }
        $flag = 'تراکنش های فروش های پرداخت شده';

        return view('back.cooperationsales.transaction_records', compact('trans', 'store', 'total', 'flag'));
    }
    public function creditTrans($id)
    {
        $trans = createstoretransaction::where('flag', 0)->where('typeoftransaction', 3)->where('store_id', $id)->latest()->paginate(20);
        $store = createstore::find($id);
        if (count($trans) > 0) {
            $total = createstoretransaction::where('flag', 0)->where('typeoftransaction', 3)->where('store_id', $id)->latest()->first()->finalprice;
        } else {
            $total = 0;
        }
        $flag = 'تراکنش های اعتبار فروشگاه';

        return view('back.cooperationsales.creadit_transaction_records', compact('trans', 'store', 'total', 'flag'));
    }
    public function transactionDetails($id)
    {
        $details = StoreTransactionDetailsModel::where('transaction_id', $id)
            ->with('transaction.payList.details.bank')
            ->first();
        $document = StoreDoumentModel::whereHas('transaction', function ($query) use ($id) {
            $query->where('store_trans_id', $id)->with('storeTransaction.storeDocument');
        })->first();
        log::info($document);
        $paidDoc = [];
        // dd($details);
        $data = $details->data;
        if ($details->transaction && $details->transaction->payList) {
            // Check if 'details' relationship exists and is an object
            $data['وضعیت'][] = $details->transaction->payList->status == 1 ? '<span class="badge badge-success">پرداخت شده</span>'
                : '<span class="badge badge-warning">انتظار پرداخت</span>';

            if ($details->transaction->payList->details && is_object($details->transaction->payList->details)) {
                $detail = $details->transaction->payList->details;
                $data['شماره پیگیری'][] = $detail->Issuetracking;
                $paidDoc = json_decode($detail->documentpayment);

                // Check if 'bank' relationship exists and is an object
                if ($detail->bank && is_object($detail->bank)) {
                    $data['بانک'][] = $detail->bank->bankname;
                }
            }

            $all_doc = json_decode($details->transaction->payList->factor);
            // log::info($document_doc);
            if ($all_doc) {
                $doc1 = [];

                foreach ($all_doc as $key) {
                    $doc[] = asset($key);
                }

                $data['سند درخواست'] = $doc;
            }
            if ($paidDoc) {
                $doc1 = [];
                foreach ($paidDoc as $key) {
                    $doc1[] = asset($key);
                }
                $data['سند پرداخت'] = $doc1;
            }
        }
        if ($document) {
            $document_doc = json_decode($document->documents);
            if ($document_doc) {

                $doc1 = [];

                foreach ($document_doc as $key) {
                    $doc[] = asset($key);
                }

                $data['سند مالی'] = $doc;
            }
        }

        return response()->json($data);
    }

    public function more($id)
    {


        // Authorize access only if the user has both permissions
        if (auth()->user()->can('cooperationsales.more') || auth()->user()->can('createcolleague.more')) {
            // $this->authorize(['cooperationsales.more', 'createcolleague.more']);
            // dd('hey');
            $shop = createstore::with('gallery')->find($id);
            // dd($shop);
            return view('back.cooperationsales.more', compact('shop'));
        } else {
            // If the user doesn't have both permissions, deny access
            abort(403);
        }
    }
    public function moreStore(Request $request, $id)
    {
        $this->authorize('cooperationsales.more');
        // dd($request->all(), $id);
        $shop = createstore::find($id);
        $path = null;

        $images = $this->updateParticipantImages($request->images);
        // dd($images);

        if ($request->file('photo')) {
            $file = $request->file('photo');

            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/',  $imageName);
            $path = '/document/createstore/' . $imageName;

            if (Storage::disk('public')->exists($shop->photo)) {
                Storage::disk('public')->delete($shop->photo);
            }
        }

        if ($request->file('gallery')) {
            $files = $request->file('gallery');
            foreach ($files as $index => $file) {
                $imageName = time() . $index .   '_store.' . $file->getClientOriginalExtension();
                $file->move('document/createstore/', $imageName);
                $path1 = '/document/createstore/' . $imageName;
                $paths[] = $path1;
            }
        }
        try {
            $shop->update([
                'photo'                  =>      $path  ?: $shop->photo,
                'nameofstore'            =>      $request->nameofstore  ?: $shop->nameofstore,
                'description'            =>      $request->description ?: $shop->description,
                'addressofstore'         =>      $request->addressofstore ?: $shop->addressofstore,
                'location'               =>      $request->location ?: $shop->location,
                'phone'                  =>      $request->phone,
            ]);

            foreach ($images as $key) {
                $shop->gallery()->create([
                    'photo'         =>  $key,
                ]);
            }
            toastr()->success('اطلاعات مجموعه شما با موفقیت ثبت شد!');
        } catch (Exception $e) {
            Log::error($e);
            toastr()->error('متأسفانه خطایی رخ داده است!');
        }
        return redirect()->back();
    }

    private function updateParticipantImages($images)
    {
        try {
            $images = explode(',', $images);
            array_shift($images); // Remove the first element if it's not empty

            // dd($images);
            $updatedImages = [];


            foreach ($images as $imageName) {
                if (Storage::exists('tmp/' . $imageName)) {
                    Storage::disk('public')->move('uploads/tmp/' . $imageName, 'document/createstore/' . $imageName);
                    $updatedImages[] = '/document/createstore/' . $imageName;
                }
            }

            return $updatedImages;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
