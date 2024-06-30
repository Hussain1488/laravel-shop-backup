<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\internalBankStoreRequest;
use App\Models\ActivityDetailsModel;
use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\bankTypeModel;
use App\Models\buyertransaction;
use App\Models\createstore;
use App\Models\createstoretransaction;
use App\Models\Gateway;
use App\Models\installmentdetails;
use App\Models\Makeinstallmentsm;
use App\Models\OperatorActivity;
use App\Models\paymentdetails;
use App\Models\PaymentListModel;
use App\Models\StoreTransactionDetailsModel;
use App\Models\User;
use Carbon\Carbon;
use CreateTypeOfAccountTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;
use Yajra\DataTables\Facades\DataTables;

use ZipArchive;
use function PHPUnit\Framework\isEmpty;

class InstallmentReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  installment report index view page with all installmnet records.
    public function index()
    {
        $this->authorize('installmentreports.index');

        $currentPaginationPart = request()->query('tab');
        // Define the default value for $payment_stat
        $payment_stat = 'wait';
        if ($currentPaginationPart == 'insta1') {
            $payment_stat = 'not_paid';
        } elseif ($currentPaginationPart == 'insta2') {
            $payment_stat = 'paid';
        }

        $perPage = 15;

        $installments = Makeinstallmentsm::where('statususer', 0)->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta');
        $installments1 = installmentdetails::where('paymentstatus', 0)->with(['installments' => function ($query) {
            $query->with('store', 'user');
        }])
            ->latest()->orderBy('installmentnumber', 'asc')
            ->paginate($perPage, ['*'], 'insta1');

        $installments2 = installmentdetails::where('paymentstatus', 1)->with(['installments' => function ($query) {
            $query->with('store', 'user');
        }])->latest()->orderBy('installmentnumber', 'asc')
            ->paginate($perPage, ['*'], 'insta2');
        // dd($installments1, $installments2);

        return view('back.installmentreports.index', compact('installments', 'installments1', 'installments2', 'payment_stat'));
    }
    public function payRequestlist()
    {
        $this->authorize('installmentreports.payRequestList');

        $perPage = 15;
        $transaction = PaymentListModel::with('store')
            ->where('status', 0)
            ->latest()
            ->paginate($perPage, ['*'], 'transaction_page');

        $transaction1 = PaymentListModel::with('store', 'details')
            ->where('status', 1)
            ->latest()
            ->paginate($perPage, ['*'], 'transaction1_page');

        $total[1] = PaymentListModel::where('status', 1)->sum('depositamount');
        $total[0] = PaymentListModel::where('status', 0)->sum('depositamount');

        $bank =  BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 21);
        })->get();

        return view('back.installmentreports.PayRequestList', compact('transaction', 'transaction1', 'total', 'bank'));
    }
    public function payReqDetails($id)
    {
        try {
            $paidRequests = PaymentListModel::with('details.bank', 'store.user')->find($id);
            $data = [
                'فروشگاه:' => $paidRequests->store->nameofstore,
                'شماره تماس:' => $paidRequests->store->user->username,
                'تاریخ درخواست:' => jdate($paidRequests->created_at)->format('Y/m/d'),
                'زمان درخواست:' => jdate($paidRequests->created_at)->format('H:i:s'),
                'مبلغ درخواست:' => number_format($paidRequests->depositamount) . ' ریال',
                'شماره شبا:' => $paidRequests->shabanumber,
                'شماره ثبت:' => $paidRequests->list_id,
                'شماره پیگیری:' => $paidRequests->details->Issuetracking,
                'تاریخ ثبت:' => jdate($paidRequests->details->created_at)->format('Y/m/d'),
                'زمان ثبت:' => jdate($paidRequests->details->created_at)->format('H:i:s'),
                'اسم بانک:' => $paidRequests->details->bank->bankname,
            ];
            $doc =  asset($paidRequests->details->documentpayment);

            return response()->json(['data' => $data, 'doc' => $doc, 'id' => $id]);
        } catch (\Exception $e) {
            // Log the error
            Log::error($e->getMessage());

            // Return an error response
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }
    public function reqDocDownload($id)
    {
        $trans = PaymentListModel::find($id);
        $zip = new ZipArchive();

        if ($zip->open(public_path('اسناد درخواست' . $trans->list_id . '.zip'), ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $doc = json_decode($trans->factor);
            foreach ($doc as $key) {
                $filePath = public_path($key);
                $zip->addFile($filePath, basename($filePath));
            }
            $zip->close();
        }
        return response()->download(public_path('اسناد درخواست' . $trans->list_id . '.zip'))->deleteFileAfterSend(true);
    }

    public function RequestPayment($id, $bank_id, $trans_id)
    {


        $payList = PaymentListModel::with('store')->find($id);
        $payList->status = 1;

        $bankt_trans = banktransaction::transaction($bank_id, $payList->depositamount, true, $trans_id, 'store');
        $payList->save();


        return true;
    }

    public function RequestPaymentStore(Request $request)
    {
        $this->authorize('installmentreports.RequestPaymentStore');
        $validator = Validator::make($request->all(), [
            'Issuetracking' => 'required',
            'nameofbank' => 'required',
            'documentpayment' => 'required',
        ]);

        if ($validator->fails()) {
            toastr()->warning('درخواست انجام نشد لطفاً فرم را به درستی پر کنید!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bank_name = $request->nameofbank;
        // dd($request->documentpayment);
        if ($request->hasFile('documentpayment')) {
            $doc_path = []; // Initialize as an empty array
            foreach ($request->file('documentpayment') as $key) {
                $imageName = time() . '_clearing.' . $key->getClientOriginalExtension();
                $key->move('document/payDetails/', $imageName);
                $doc_path[] = '/document/payDetails/' . $imageName;
            }
            $path = json_encode($doc_path); // Use json_encode to convert the array to a JSON string
        } else {
            $path = '';
        }

        $payList = PaymentListModel::find($request->pay_list_id);

        $store = createstore::find($payList->store_id);
        $description = 'پرداخت درخواست واریز';
        $trans_data = [
            'تراکنش:' => $description,
            'توسط:' => Auth::user()->username,
            'مقدار تراکنش' => number_format($payList->depositamount) . ' ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        $data = [
            'فروشگاه' => $store->nameofstore,
            'مقدار پرداخت' => number_format($payList->depositamount) . ' ریال',
            'شماره پیگیری بانک' => $request->Issuetracking,
        ];
        try {
            DB::beginTransaction();
            paymentdetails::create([
                'list_of_payment_id' => $request->pay_list_id,
                'Issuetracking' => $request->Issuetracking,
                'bank_id'  => $bank_name,
                'documentpayment'  => $path,
            ]);

            $operator_id = OperatorActivity::createActivity($store->user->id, 'PAY_REQUEST_PAYMENT');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            // $trans_id = createstoretransaction::storeTransaction($store, $payList->depositamount, true, 1, 2, null, null, $description);
            // StoreTransactionDetailsModel::createDetail($trans_id, $trans_data);

            $this->RequestPayment($request->pay_list_id, $request->nameofbank, $payList->trans_id);
            DB::commit();
            toastr()->success('اطلاعات پرداخت موفقیت آمیز ذخیره شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('مشکلی در ذخیره اطلاعات پرداخت رخ داده است.' . $e);
        }
        return redirect()->back();
    }
    //  bank transaction list view page
    public function banktransaction()
    {

        $transaction = banktransaction::with('bank')->latest()->get();
        $total  = collect($transaction)->sum('transactionprice');

        return view('back.installmentreports.banktransaction', compact('transaction', 'total'));
    }

    public function bankList()
    {
        $this->authorize('installmentreports.banklist');

        $listbank = BankAccount::with('account_type')->latest()->paginate(10);
        // dd($listbank);
        return view('back.installmentreports.bank_list', compact('listbank'));
    }

    public function createinternalaccount()
    {
        $this->authorize('installmentreports.createinternalaccount');
        // $listbank = BankAccount::all();
        $types = bankTypeModel::all();
        $gateways = Gateway::whereDoesntHave('bank')->active()->get(['id', 'name']);

        // dd($gateways);


        return view('back.installmentreports.createinternalaccount', compact('types', 'gateways'));
    }
    public function storebank(internalBankStoreRequest $request)
    {
        // dd($request->all());
        $this->authorize('installmentreports.storebank');
        bankTypeModel::find($request->account_type_id)->name;
        $data = [
            'اسم بانک' => $request->bankname,
            'شماره بانک' => $request->accountnumber,
            'ماهیت حساب' => bankTypeModel::find($request->account_type_id)->name,
        ];
        try {
            DB::beginTransaction();
            BankAccount::create([
                'gateway_id' => $request->gateway,
                'bankname' => $request->bankname,
                'accountnumber' => $request->accountnumber,
                'account_type_id' => $request->account_type_id,
            ]);
            $operator_id = OperatorActivity::createActivity(null, 'CREATE_INTERNAL_ACCOUNT');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            DB::commit();
            toastr()->success('حساب بانکی با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->warning('خطایی در ایجاد حساب بانکی جدید رخ داده است!' . $e);
        }
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    // filtering accordint to phone number in installments view page in waiting to validate tab.
    public function filter(Request $request)
    {
        // dd($request->all());
        $payment_stat = 'wait';
        $perPage = 15;
        $user = User::where('username', 'like', '%' . $request->filter . '%')->get();
        $installments = Makeinstallmentsm::with("store", "user")->where('statususer', 0)->whereIn('user_id', $user->pluck('id'))->latest()->paginate($perPage, ['*'], 'insta');

        if ($user->isEmpty()) {
            toastr()->warning('قسطی با شماره وارد شده یافت نشد.');
            return redirect()->back();
        } else {
            $installments1 = installmentdetails::where('paymentstatus', 0)->with(['installments' => function ($query) {
                $query->with('store', 'user');
            }])
                ->latest()
                ->paginate($perPage, ['*'], 'insta1');
            $installments2
                = $installments2 = installmentdetails::where('paymentstatus', 1)->with(['installments' => function ($query) {
                    $query->with('store', 'user');
                }])->latest()
                ->paginate($perPage, ['*'], 'insta2');

            return view('back.installmentreports.index', compact('installments', 'installments1', 'installments2', 'payment_stat'));
            // dd($installments);
        }
    }

    // filtering accordint to phone number in installments view page in not_paid to validate tab.
    public function filter1(Request $request)
    {

        // dd($request->all());
        $payment_stat = 'not_paid';
        $perPage = 15;
        $payment_stat = 'not_paid';
        $all_installments = Makeinstallmentsm::with("store", "user")->get();

        $user = User::where('username', 'like', '%' . $request->filter1 . '%')->get();
        // dd($user);
        $installments1 = installmentdetails::where('paymentstatus', 0)->whereHas('installments', function ($query) use ($user) {
            $query->whereIn('user_id', $user->pluck('id'));
        })->with(['installments' => function ($query) use ($user) {
            $query->with('store', 'user')->whereIn('user_id', $user->pluck('id'));
        }])->latest()
            ->paginate($perPage, ['*'], 'insta1');


        if (!$installments1->count() > 0) {
            toastr()->warning('قسطی با شماره وارد شده یافت نشد.');
            return redirect()->back();
        } else {
            $installments = Makeinstallmentsm::where('statususer', 0)->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta1');
            $installments2 = installmentdetails::where('paymentstatus', 1)->with(['installments' => function ($query) {
                $query->with('store', 'user');
            }])->latest()
                ->paginate($perPage, ['*'], 'insta2');
            return view('back.installmentreports.index', compact('installments', 'installments1', 'installments2', 'payment_stat'));
            // dd($installments);
        }
    }
    // filtering accordint to phone number in installments view page in paid to validate tab.

    public function filter2(Request $request)
    {

        // dd($request->all());
        $payment_stat = 'paid';
        $perPage = 15;

        $user = User::where('username', 'like', '%' . $request->filter1 . '%')->get();
        // dd($user);
        $installments2 = installmentdetails::where('paymentstatus', 1)->whereHas('installments', function ($query) use ($user) {
            $query->whereIn('user_id', $user->pluck('id'));
        })->with(['installments' => function ($query) use ($user) {
            $query->with('store', 'user')->whereIn('user_id', $user->pluck('id'));
        }])->latest()
            ->paginate($perPage, ['*'], 'insta1');
        if (!$installments2->count() > 0) {
            toastr()->warning('قسطی با شماره وارد شده یافت نشد.');
            return redirect()->back();
        } else {
            $installments = Makeinstallmentsm::where('statususer', 0)->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta1');
            $installments1 = installmentdetails::where('paymentstatus', 0)->with(['installments' => function ($query) {
                $query->with('store', 'user');
            }])->latest()
                ->paginate($perPage, ['*'], 'insta2');
            return view('back.installmentreports.index', compact('installments', 'installments1', 'installments2', 'payment_stat'));
            // dd($installments);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //  showing the installmnets of specific store's installments
    public function show_shop_installments($id, $slug)
    {
        // dd($id, $slug);
        $perPage = 15;
        if ($slug == 'wait') {
            $payment_stat = 'wait';
        } else if ($slug == 'not_paid') {
            $payment_stat = 'not_paid';
        } else if ($slug == 'paid') {
            $payment_stat = 'paid';
        }
        $currentPaginationPart = request()->query('tab');
        // Define the default value for $payment_stat
        $payment_stat = 'wait';
        if ($currentPaginationPart == 'insta1') {
            $payment_stat = 'not_paid';
        } elseif ($currentPaginationPart == 'insta2') {
            $payment_stat = 'paid';
        }
        $shop = createstore::where('id', $id)->first();
        // dd($shop);
        $installments = Makeinstallmentsm::where('statususer', 0)->where('store_id', $id)->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta');
        $installments1 = installmentdetails::where('paymentstatus', 0)->whereHas('installments', function ($query) use ($shop) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 0);
        })->with(['installments' => function ($query) use ($shop) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 0);;
        }])->latest()->orderBy('installmentnumber', 'asc')->paginate($perPage, ['*'], 'insta1');

        $installments2 = installmentdetails::where('paymentstatus', 1)->whereHas('installments', function ($query) use ($shop) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 1);;
        })->with(['installments' => function ($query) use ($shop) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 1);;
        }])->latest()->orderBy('installmentnumber', 'asc')->paginate($perPage, ['*'], 'insta2');

        return view('back.installmentreports.shop_installments', compact('installments', 'installments1', 'installments2', 'payment_stat', 'shop'));
    }

    //  filtering the records of installmnets of specific store's installments accorgin to  user ID

    public function show_shop_installments_filter(Request $request)
    {
        $shop = createstore::where('id', $request->store)->first();
        // dd($request->all());
        $perPage = 15;
        $payment_stat = 'wait';
        $installments = Makeinstallmentsm::where('statususer', 0)->where('store_id', $request->store)->where('user_id', $request->user)
            ->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta');
        $installments1 = installmentdetails::where('paymentstatus', 0)->whereHas('installments', function ($query) use ($shop, $request) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 0)->where('user_id', $request->user);
        })->with(['installments' => function ($query) use ($shop, $request) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 0)->where('user_id', $request->user);
        }])->latest()->paginate(
            $perPage,
            ['*'],
            'insta1'
        );
        $installments2 = installmentdetails::where('paymentstatus', 1)->whereHas('installments', function ($query) use ($shop, $request) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 1)->where('user_id', $request->user);
        })->with(['installments' => function ($query) use ($shop, $request) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 1)->where('user_id', $request->user);
        }])->latest()->paginate($perPage, ['*'], 'insta2');

        $currentPaginationPart = request()->query('tab');

        $payment_stat = 'wait';
        if ($currentPaginationPart == 'insta1') {
            $payment_stat = 'not_paid';
        } elseif ($currentPaginationPart == 'insta2') {
            $payment_stat = 'paid';
        }

        return view('back.installmentreports.shop_installments', compact('installments', 'installments1', 'installments2', 'payment_stat', 'shop'));
    }

    //  filtering the records of installmnets of specific store's installments accorgin to phone number input

    public function show_shop_installments_filter_name(Request $request)
    {
        // dd($request->all());
        $payment_stat = $request->payment_stat;
        $user = User::where('username', 'like', '%' . $request->filter . '%')->get();
        $shop = createstore::where('id', $request->store)->first();

        $perPage = 15;
        if ($user->isEmpty()) {
            toastr()->warning("هیچ کاربری با شماره وارده یافت نشد.");
            return redirect()->back();
        }
        $installments = Makeinstallmentsm::where('statususer', 0)->where('store_id', $request->store)->whereIn('user_id', $user->pluck('id'))
            ->with("store", "user")->latest()->paginate($perPage, ['*'], 'insta');

        $installments1 = installmentdetails::where('paymentstatus', 0)->whereHas('installments', function ($query) use ($shop, $user) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 0)->whereIn('user_id', $user->pluck('id'));
        })->with(['installments' => function ($query) use ($shop, $user) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 0)->whereIn('user_id', $user->pluck('id'));
        }])->latest()->paginate(
            $perPage,
            ['*'],
            'insta1'
        );
        $installments2 = installmentdetails::where('paymentstatus', 1)->whereHas('installments', function ($query) use ($shop, $user) {
            $query->where('store_id', $shop->id)->where('paymentstatus', 1)->whereIn('user_id', $user->pluck('id'));
        })->with(['installments' => function ($query) use ($shop, $user) {
            $query->with('store', 'user')->where('store_id', $shop->id)->where('paymentstatus', 1)->whereIn('user_id', $user->pluck('id'));
        }])->latest()->paginate($perPage, ['*'], 'insta2');

        $currentPaginationPart = request()->query('tab');
        $payment_stat = 'wait';
        if ($currentPaginationPart == 'insta1') {
            $payment_stat = 'not_paid';
        } elseif ($currentPaginationPart == 'insta2') {
            $payment_stat = 'paid';
        }
        return view('back.installmentreports.shop_installments', compact('installments', 'installments1', 'installments2', 'payment_stat', 'shop'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // destroying the specific installments.
    public function refuse($id)
    {
        try {
            DB::beginTransaction();
            Makeinstallmentsm::refuse($id);
            DB::commit();
            toastr()->success('قسط مورد نظر با موفقیت حذف شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('مشکلی در حذف فروش  رخ داده است.' . $e);
        }
        return redirect()->back();
    }

    public function transactionFilter($id)
    {
        $this->authorize('installmentreports.banklist');
        $title = BankAccount::find($id)->bankname;
        $transactions = BankTransaction::where('bank_id', $id)
            ->with('bank.account_type', 'buyerTransaction.user', 'storeTransaction.store.user')
            ->latest()
            ->get();

        $log = false;

        $total = $transactions->first()->bankbalance ?? 0;

        foreach ($transactions as $key) {
            if ($key->storeTransaction != null) {
                $key->log = $key->storeTransaction !== null;
            }
        }


        return view('back.installmentreports.banktransaction', compact('id', 'total', 'title', 'log'));
    }
    public function transactionFilterData(Request $request)
    {
        try {
            $trans = BankTransaction::query()->with('bank.account_type', 'buyerTransaction.user', 'storeTransaction.store.user');
            $trans = $trans->where('bank_id', $request->input('bankId'))->latest();
            $start = null;
            $end = null;

            if ($request->has('start_date') && $request->input('start_date') !== null) {
                $start = Jalalian::fromFormat('Y-m-d', $request->input('start_date'))->toCarbon();
            }

            if ($request->has('end_date') && $request->input('end_date') !== null) {
                $end = Jalalian::fromFormat('Y-m-d', $request->input('end_date'))->toCarbon()->endOfDay();
            }

            if ($start !== null && $end !== null) {
                // Both start_date and end_date are provided
                $trans->whereBetween('created_at', [$start, $end]);
            } elseif ($start !== null) {
                // Only start_date is provided
                $trans->where('created_at', '>=', $start);
            } elseif ($end !== null) {
                // Only end_date is provided
                $trans->where('created_at', '<=', $end);
            }


            $result = DataTables::eloquent($trans)
                ->addColumn('counter', function () {
                    // static $counter = 0; // Use static to persist the counter across rows
                    return null;
                })
                ->addColumn('user', function ($trans) {
                    return $trans->store_trans_id
                        ? $trans->storeTransaction->store->nameofstore
                        : ($trans->buyer_trans_id ? $trans->buyerTransaction->user->first_name . ' ' . $trans->buyerTransaction->user->last_name : 'ندارد');
                })
                ->addColumn('source', function ($trans) {
                    return $trans->user_trans_id
                        ? 'کاربر'
                        : 'اپراتور';
                })
                ->addColumn('username', function ($trans) {
                    return $trans->store_trans_id
                        ? $trans->storeTransaction->store->user->username
                        : ($trans->buyer_trans_id ? $trans->buyerTransaction->user->username : 'ندارد');
                })
                ->filterColumn('username', function ($query, $keyword) {
                    $query->where(function ($query) use ($keyword) {
                        $query->whereHas('storeTransaction.store.user', function ($query) use ($keyword) {
                            $query->where('username', 'like', '%' . $keyword . '%');
                        })
                            ->orWhereHas('buyerTransaction.user', function ($query) use ($keyword) {
                                $query->where('username', 'like', '%' . $keyword . '%');
                            });
                    });
                })
                ->addColumn('transactionprice', function ($trans) {
                    return $trans->transactionprice;
                })->addColumn('status', function ($trans) {
                    return $trans->type;
                })
                ->addColumn('bankbalance', function ($trans) {
                    return $trans->bankbalance;
                })
                ->addColumn('transaction_date', function ($trans) {
                    return [
                        'date' => jdate($trans->created_at)->format('Y/m/d'),
                        'time' => $trans->created_at->format('H:i:s'),
                    ];
                })
                ->addColumn('transaction_details', function ($trans) {
                    return $trans->id;
                })
                ->rawColumns(['username']) // Mark 'username' as raw HTML
                ->make(true);
            // Log::info($result);

            return $result;
        } catch (\Exception $e) {
            \Log::error('Error in transactionFilterData: ' . $e->getMessage());
            // You can return an error response or handle it according to your application's needs.
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function transactionDetails($id)
    {
        $this->authorize('installmentreports.banklist');
        $trans = BankTransaction::with('userDocument', 'dobtorDocument', 'creditorDocument', 'storeDocument')->where('id', $id)->first();
        $data = [];
        $type = '';
        if ($trans->buyer_trans_id) {
            $trans_buyer = buyertransaction::find($trans->buyer_trans_id);
            $data = [
                'اسم کاربر' => $trans_buyer->user->first_name . ' ' . $trans_buyer->user->last_name,
                'شماره تماس کاربر' => $trans_buyer->user->username,
                'توضیح تراکنش' => $trans_buyer->description,
                'نوع تراکنش' => $trans_buyer->flag == 1 ? 'کیف پول کاربر' : 'اعتبار خرید',
                'وضعیت' => $trans_buyer->typeoftransaction == 1 && $trans_buyer->flag == 1 ? 'افزایش موجودی کیف پول کاربر' : ($trans_buyer->typeoftransaction == 1 && $trans_buyer->flag == 0 ? 'افزایش اعتبار کاربر' : ($trans_buyer->typeoftransaction == 0 && $trans_buyer->flag == 1 ? 'کاهش موجودی کیف پول کاربر' : 'کاهش اعتبار خرید کاربر')),
                'مبلغ تراکنش' => number_format($trans_buyer->price) . ' ریال',
                'شماره سند' => $trans_buyer->documentnumber,
                'تاریخ تراکنش' => jdate($trans_buyer->created_at)->format('Y/m/d'),
                'زمان تراکنش' =>  $trans_buyer->created_at->format('H:i:s'),
            ];
            if ($trans->userDocument) {
                $doc = json_decode($trans->userDocument->documents, true);
                $data['سند'] = [];
                foreach ($doc as $key) {
                    $data['سند'][] = asset($key);
                }
            }
            $type = 'buyer';
        } else if ($trans->store_trans_id) {
            $store_trans = createstoretransaction::find($trans->store_trans_id);
            $data = [
                'اسم کاربر' => $store_trans->store->user->first_name . ' ' . $store_trans->store->user->last_name,
                'اسم فروشگاه' => $store_trans->store->nameofstore,
                'شماره تماس کاربر' => $store_trans->store->user->username,
                'توضیح تراکنش' => $store_trans->description,
                'مبلغ تراکنش' => number_format($store_trans->price) . ' ریال',
                'شماره سند' => $store_trans->documentnumber,
                'بانک' => $store_trans->bankTransaction->bank->accountnumber,
                'تاریخ تراکنش' => jdate($store_trans->created_at)->format('Y/m/d'),
                'زمان تراکنش' =>  $store_trans->created_at->format('H:i:s'),
            ];
            if ($trans->storeDocument) {
                $doc = json_decode($trans->storeDocument->documents, true);
                $data['سند'] = [];
                foreach ($doc as $key) {
                    $data['سند'][] = asset($key);
                }
            }
            $type = 'store';
        } else if ($trans->dobtorDocument) {
            $account_trans = $trans->dobtorDocument;
            $data = [
                'اسم حساب' => $trans->bank->bankname,
                'شماره حساب' => $trans->bank->accountnumber,
                'توضیح تراکنش' => $account_trans->description,
                'مبلغ تراکنش' => number_format($trans->transactionprice) . ' ریال',
                'شماره سند' => $account_trans->numberofdocuments,
                'نوع تراکنش' => $trans->type == 'withdraw' ? '<span class="badge badge-warning">کاهش موجودی</span>' : '<span class="badge badge-success">افزایش موجودی</span>',
                'تاریخ تراکنش' => jdate($account_trans->created_at)->format('Y/m/d'),
                'زمان تراکنش' =>  $account_trans->created_at->format('H:i:s'),
            ];

            $doc = json_decode($account_trans->documents, true);
            $data['سند'] = [];
            foreach ($doc as $key) {
                $data['سند'][] = asset($key);
            }
            $type = 'account';
        } else if ($trans->creditorDocument) {
            $account_trans = $trans->creditorDocument;
            $data = [
                'اسم حساب' => $trans->bank->bankname,
                'شماره حساب' => $trans->bank->accountnumber,
                'توضیح تراکنش' => $account_trans->description,
                'مبلغ تراکنش' => number_format($trans->transactionprice) . ' ریال',
                'شماره سند' => $account_trans->numberofdocuments,
                'نوع تراکنش' => $trans->type == 'withdraw' ? '<span class="badge badge-warning">کاهش موجودی</span>' : '<span class="badge badge-success">افزایش موجودی</span>',
                'تاریخ تراکنش' => jdate($account_trans->created_at)->format('Y/m/d'),
                'زمان تراکنش' =>  $account_trans->created_at->format('H:i:s'),
            ];
            $doc = json_decode($account_trans->documents, true);
            $data['سند'] = [];
            foreach ($doc as $key) {
                $data['سند'][] = asset($key);
            }
            $type = 'account';
        } else {
            return response('error');
        }
        return response()->json(['data' => $data, 'type' => $type]);
    }


    public function paidList()
    {

        $this->authorize('installmentreports.paidList');
        $paidList = paymentdetails::with('payments.store')->latest()->paginate(20);

        return view('back.installmentreports.paidList', compact('paidList'));
    }
}
