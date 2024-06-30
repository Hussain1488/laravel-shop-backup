<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\ColleagueCreateDocument;
use App\Http\Requests\Back\ColleagueReAccreditionRequest;
use App\Http\Requests\Back\CreateColleagueIndexRequest;
use App\Http\Requests\Back\CreateShopRequest;
use App\Http\Requests\Back\ShopShopUpdateRequest;
use App\Models\AccountDocumentModel;
use App\Models\ActivityDetailsModel;
use App\Models\BankAccount;
use App\Models\banktransaction;
use App\Models\bankTypeModel;
use App\Models\buyertransaction;
use App\Models\createdocument;
use App\Models\StoreTransactionDetailsModel;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\createstore;
use App\Models\createstoretransaction;
use App\Models\OperatorActivity;
use App\Models\StoreDoumentModel;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;
use stdClass;
use ZipArchive;

class CreateColleagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // going to create colleague index view page
    public function index()
    {
        $this->authorize('createcolleague.index');
        // $users = User::where('level'$, 'user', 'wallet')->get();
        return view('back.createcolleague.index');
    }

    public function shopList()
    {
        $this->authorize('createcolleague.shopList');

        $store = createstore::with('user')->latest()->paginate(15);
        return view('back.createcolleague.shop_list', compact('store'));
    }
    public function shopListFilter(Request $request)
    {

        $filter = $request->filter;
        $store = CreateStore::where(function ($query) use ($filter) {
            $query->where('nameofstore', 'like', '%' . $filter . '%');
        })->orWhereHas('user', function ($query) use ($filter) {
            $query->where('username', 'like', '%' . $filter . '%');
        })->with('user')->latest()->paginate(15);

        if ($store->isEmpty()) {
            $store = createstore::with('user')->latest()->paginate(15);
            toastr()->error('هیچ فروشگاهی برای شما یافت نشد.');
        }
        return view('back.createcolleague.shop_list', compact('store'));
    }

    public function shopedit($id)
    {
        $this->authorize('createcolleague.shopedit');

        $store = createstore::with('user')->find($id);
        $user = User::get();
        return view('back.createcolleague.shop_edit', compact('store', 'user'));
    }

    public function shopUpdate(ShopShopUpdateRequest $request, $id)
    {
        $this->authorize('createcolleague.shopedit');

        $store = createstore::find($id);

        $carbonDate = null;
        $conrn_job_reccredite = intval(str_replace(',', '', $request->conrn_job_reccredite));

        if ($request->enddate != null) {
            $carbonDate = Jalalian::fromFormat('Y-m-d', $request->enddate)->toCarbon()->format('Y-m-d');
        }
        $paths = json_decode($store->uploaddocument);
        $docPath = '';
        if ($request->file('uploaddocument')) {
            $files = $request->file('uploaddocument');
            foreach ($files as $file) {
                $imageName = time() . '_store.' . $file->getClientOriginalExtension();
                $file->move('document/createstore/', $imageName);
                $path = '/document/createstore/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $path = null;
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $imageName = time() . '_store.' . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'employee' . $imageName);
            $path = '/document/createstore/' . 'employee' . $imageName;
            Storage::disk('public')->delete($store->photo);
        }
        $originalData = $store->getOriginal();
        // dd($originalData);
        $englishToPersian = [
            'nameofstore'           =>     'اسم فروشگاه',
            'feepercentage'         =>     'مقدار کارمز',
            'addressofstore'        =>     'آدرس فروشگاه',
            'settlementtime'        =>     'زمان تسویه',
            'enddate'               =>     'ختم قرارداد',
            'conrn_job_reccredite'  =>     'اعتبار ماهانه فروشگاه',
            // Add more field mappings as needed
        ];
        $changes = [];
        foreach ($originalData as $key => $value) {
            if ($store->$key != $value) {
                $changes[$key] = [
                    'old' => $value,
                    'new' => $store->$key,
                ];
            }
        }
        $data = ['اسم فروشگاه' => $originalData['nameofstore']];
        foreach ($changes as $key => $change) {
            $fieldPersianName = $englishToPersian[$key] ?? $key;
            // Check if the field has a Persian translation
            if ($fieldPersianName != $key) {
                $data[$fieldPersianName . ' (قبلی)'] = $change['old'];
                $data[$fieldPersianName . ' (جدید)'] = $change['new'];
            }
        }
        try {
            DB::beginTransaction();
            $store->update([
                'nameofstore'           =>     $request->nameofstore,
                'photo'                 =>     $path ?: $store->photo,
                'addressofstore'        =>     $request->addressofstore,
                'feepercentage'         =>     $request->feepercentage,
                'settlementtime'        =>     $request->settlementtime,
                'enddate'               =>     $carbonDate != null ? $carbonDate : $store->enddate,
                'uploaddocument'        =>     $docPath,
                'conrn_job_reccredite'  =>     $conrn_job_reccredite,
                'no_profit_installment' =>     $request->no_profit_installment,
                'no_profit_fee'         =>     $request->no_profit_fee,
            ]);

            // dd($store->user_id);
            $operator_id = OperatorActivity::createActivity($store->user_id, 'EDIT_STORE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            DB::commit();
            toastr()->success('فروشگاه با موفقیت اصلاح شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->warning('عملیات اصلاح فروشگاه انجام نشد!' . $e);
        }
        return redirect(route('admin.createcolleague.shopList'));
    }




    public function show($id)
    {
        $this->authorize('createcolleague.show');

        $store = createstore::find($id);
        $doc = json_decode($store->uploaddocument);

        return view('back.createcolleague.show', compact('store', 'doc'));
    }
    public function fileDownload($id)
    {
        $this->authorize('createcolleague.show');
        $store = createstore::find($id);
        $zip = new ZipArchive;
        if ($zip->open(public_path('اسناد فروشگاه ' . $store->nameofstore . '.zip'), ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $doc = json_decode($store->uploaddocument);

            foreach ($doc as $document) {
                $filePath = public_path($document);

                $zip->addFile($filePath, basename($filePath));
            }

            $zip->close();

            // Download the zip file
            return response()->download(public_path('اسناد فروشگاه ' . $store->nameofstore . '.zip'))->deleteFileAfterSend(true);
        } else {
            // Handle the case where ZipArchive could not be opened
            return response()->json(['error' => 'Failed to create or open the zip file'], 500);
        }
    }

    public function create()
    {
        $this->authorize('createcolleague.create');
        $accounts = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 23);
        })->get();
        return view('back.createcolleague.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // storing new store
    public function store(CreateShopRequest $request)
    {
        $this->authorize('createcolleague.create');

        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 25);
        })->first();
        if (!$bank_id) {
            toastr()->error('شما هیچ بانکی با ماهیت واسط اعتبار فروشگاه ها ندارید. لطفا ایجاد نموده دوباره تلاش کنید.');
            return redirect()->back();
        }

        $path = null;
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $imageName = time() . $file->getClientOriginalExtension();
            $file->move('document/createstore/', 'shop' . $imageName);
            $path = '/document/createstore/' . 'shop' . $imageName;
        }

        $storecredit = intval(str_replace(',', '', $request->storecredit));
        $carbonDate = Jalalian::fromFormat('Y-m-d', $request->enddate)->toCarbon()->format('Y-m-d');
        $docPath = '';
        if ($request->file('uploaddocument')) {
            $files = $request->file('uploaddocument');
            $paths = [];
            foreach ($files as $file) {
                $imageName = time() . '_store.' . $file->getClientOriginalExtension();
                $file->move('document/createstore/', $imageName);
                $path = '/document/createstore/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $description = 'اعتبار دهی اولیه فروشگاه';
        $trans_data = [
            'تراکنش:' => $description,
            'اسم فروشگاه:' => $request->nameofstore,
            'توسط:' => Auth::user()->username,
            'مقدار اعتبار:' => number_format($storecredit) . 'ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        $data = [
            'اسم فروشگاه' => $request->nameofstore,
            'مقدار اعتبار داده شده' => number_format($storecredit) . ' ریال',
            'مقدار کارمز' => $request->feepercentage . ' درصد',
            'شماره حساب درآمد' => BankAccount::find($request->account_id)->accountnumber,
        ];
        try {
            DB::beginTransaction();
            $store = createstore::create([
                'storecredit'            =>     $storecredit,
                'user_id'                =>     $request->user_id,
                'photo'                  =>     $path,
                'nameofstore'            =>     $request->nameofstore,
                'addressofstore'         =>     $request->addressofstore,
                'feepercentage'          =>     $request->feepercentage,
                'settlementtime'         =>     $request->settlementtime,
                'enddate'                =>     $carbonDate,
                'location'               =>     $request->location,
                'uploaddocument'         =>     $docPath,
                'account_id'             =>     $request->account_id,
                'conrn_job_reccredite'   =>     $storecredit,
                'no_profit_installment'  =>     $request->no_profit_installment,
                'no_profit_fee'          =>     $request->no_profit_fee,
            ]);
            $trans_id = createstoretransaction::storeTransaction($store, $storecredit, true, 3, 0, null, null, $description);
            StoreTransactionDetailsModel::createDetail($trans_id, $trans_data);
            $bankt_tras = banktransaction::transaction($bank_id->id, $storecredit, false, $trans_id, 'store');

            $operator_id = OperatorActivity::createActivity($request->user_id, 'CREATE_STORE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);

            DB::commit();

            toastr()->success('!فروشگاه با موفقیت ایجاد شد.');
            return redirect()->route('admin.createcolleague.shopList');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->warning('مشکلی در ایجاد فروشگاه رخ داده است!' . $e);
            return redirect()->back();
        }
    }

    public function colleagueCreditStore(CreateColleagueIndexRequest $request)
    {

        $carbonDate = Jalalian::fromFormat('Y-m-d', $request->enddate)->toCarbon()->format('Y-m-d');
        $bank_id =   BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 26);
        })->first();
        if (!$bank_id) {
            toastr()->error('شما هیچ بانکی با ماهیت اعتبار خرید خریدارها ندارید. لطفا ایجاد نموده دوباره تلاش کنید.');
            return redirect()->back();
        }
        $userUpdate = User::find($request->user_id);
        $paths[] = json_decode($userUpdate->documents);
        $docPath = '';
        if ($request->file('documents')) {
            $files = $request->file('documents');
            $paths = [];
            foreach ($files as $file) {

                $imageName = time() . '_usercredit.' . $file->getClientOriginalExtension();
                $file->move('document/usercredite/', $imageName);
                $path = '/document/usercredite/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $number = createdocument::count();
        if ($number > 0 && $number != 0) {
            $number = createdocument::latest()->first()->numberofdocuments + 1;
        } else {
            $number = 10000;
        }
        if ($request->trans_type == 'increase') {
            // dd('hey');
            $this->userCreditIncrease($userUpdate, $carbonDate, $docPath, $number, $request, $bank_id);
        } else {
            if ($request->purchasecredit <= $userUpdate->purchasecredit) {
                $this->userCreditDecrease($userUpdate, $carbonDate, $docPath, $number, $request, $bank_id);
            } else {
                toastr()->warning('مقدار درخواستی برای کاهش اعتبار، از اعتبار کاربر بیشتر است!');
                return redirect()->back();
            }
        }
        return redirect()->back();
    }
    public function userCreditIncrease($userUpdate, $carbonDate, $docPath, $number, $request, $bank_id)
    {

        $data = [
            'اعتبار قبلی' => number_format($userUpdate->purchasecredit) . ' ریال',
            'مقدار افزایش اعتبار' => number_format($request->purchasecredit) . ' ریال',
        ];

        try {
            DB::beginTransaction();
            $userUpdate->purchasecredit += $request->purchasecredit;
            $userUpdate->enddate = $carbonDate;

            $operator_id = OperatorActivity::createActivity($userUpdate->id, 'BUYER_CREDIT');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            // public function transaction($user, $amount, $status, $flag, $type)
            $buyer_trans = buyertransaction::transaction($userUpdate, $request->purchasecredit, true, '0', '1', 'افزایش اعتبار توسط اپراتور');
            // transaction($bank_id, $creditAmount, $status, $trans_id)
            $bank_trans = banktransaction::transaction($bank_id->id, $request->purchasecredit, false, $buyer_trans->id, 'user');

            createdocument::create([
                'transaction_id' => $bank_trans->id,
                'user_id' => $userUpdate->id,
                'description' => 'افزایش اعتبار خریدار توسط اپراتور',
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);

            $userUpdate->save();
            DB::commit();
            toastr()->success('اعتبار دهی به کاربر با موفقیت انجام شد.');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            toastr()->warning('اعتبار دهی به کاربر با خطا روبرو شد!' . $e);
            return false;
        }
    }

    public function userCreditDecrease($userUpdate, $carbonDate, $docPath, $number, $request, $bank_id)
    {
        $data = [
            'اعتبار قبلی' => number_format($userUpdate->purchasecredit) . ' ریال',
            'مقدار کاهش اعتبار' => number_format($request->purchasecredit) . ' ریال',
        ];

        try {
            DB::beginTransaction();
            $userUpdate->purchasecredit -= $request->purchasecredit;
            $userUpdate->enddate = $carbonDate;

            $operator_id = OperatorActivity::createActivity($userUpdate->id, 'BUYER_CREDIT');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            // public function transaction($user, $amount, $status, $flag, $type)
            $buyer_trans = buyertransaction::transaction($userUpdate, $request->purchasecredit, false, '0', '1', 'کاهش اعتبار توسط اپراتور');
            // transaction($bank_id, $creditAmount, $status, $trans_id)
            $bank_trans = banktransaction::transaction($bank_id->id, $request->purchasecredit, true, $buyer_trans->id, 'user');

            createdocument::create([
                'transaction_id' => $bank_trans->id,
                'user_id' => $userUpdate->id,
                'description' => 'کاهش اعتبار خریدار توسط اپراتور',
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);

            $userUpdate->save();
            DB::commit();
            toastr()->success('  کاهش اعتبار کاربر با موفقیت انجام شد.');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            toastr()->warning('  کاهش اعتبار کاربر با خطا روبرو شد!' . $e);
            return false;
        }
    }



    // adding credit to store view page
    public function reaccreditationIndex()
    {
        $this->authorize('createcolleague.reaccreditation.index');
        $store = createstore::with('user')->get();
        return view('back.createcolleague.reaccreditation', compact('store'));
    }


    public function reaccreditationStore(ColleagueReAccreditionRequest $request)
    {

        $store = createstore::with('user')->find($request->select_store);


        $bank_id = BankAccount::whereHas('account_type', function ($query) {
            $query->where('code', 25);
        })->first();
        if (!$bank_id) {
            toastr()->error('شما هیچ بانکی با ماهیت واسط اعتبار فروشگاه ها ندارید. لطفا ایجاد نموده دوباره تلاش کنید.');
            return redirect()->back();
        }
        if ($request->trans_type == 'increase') {
            $this->storeIncreaseCredit($store, $request, $bank_id);
        } else {
            $this->storeDecreaseCreadit($store, $request, $bank_id);
        }

        return redirect()->back();
    }
    public function storeIncreaseCredit($store, $request, $bank_id)
    {
        $ex_credit = $store->storecredit;
        $store->storecredit += $request->storecredit;
        $description = 'افزایش اعتبار فروشگاه';
        $trans_data = [
            'تراکنش:' => $description,
            'توسط:' => Auth::user()->username,
            'اعتبار قبلی' => number_format($ex_credit) . ' ریال',
            'مقدار افزایش اعتبار' => number_format($request->storecredit) . ' ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        $data = [
            'اسم فروشگاه' => $store->nameofstore,
            'اعتبار قبلی' => number_format($ex_credit) . ' ریال',
            'مقدار افزایش اعتبار' => number_format($request->storecredit) . ' ریال',
        ];
        try {
            DB::beginTransaction();

            $operator_id = OperatorActivity::createActivity($store->user->id, 'STORE_CREDIT');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            $trans_id = createstoretransaction::storeTransaction($store, $request->storecredit, true, 3, 0, null, null, $description);
            StoreTransactionDetailsModel::createDetail($trans_id, $trans_data);
            $bank_trans = banktransaction::transaction($bank_id->id, $request->storecredit, false, $trans_id, 'store');
            $store->save();

            DB::commit();
            toastr()->success('افزایش اعتبار فروشگاه با موفقیت انجام شد.');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('مشکلی در افزایش اعتبار فروشگاه رخ داده است!' . $e);
            return false;
        }
    }
    public function storeDecreaseCreadit($store, $request, $bank_id)
    {
        $ex_credit = $store->storecredit;
        $store->storecredit -=  $request->storecredit;
        $description = 'کاهش اعتبار فروشگاه';
        $trans_data = [
            'تراکنش:' => $description,
            'توسط:' => Auth::user()->username,
            'اعتبار قبلی' => number_format($ex_credit) . ' ریال',
            'مقدار کاهش اعتبار' => number_format($request->storecredit) . ' ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        $data = [
            'اسم فروشگاه' => $store->nameofstore,
            'اعتبار قبلی' => number_format($ex_credit) . ' ریال',
            'مقدار کاهش اعتبار' => number_format($request->storecredit) . ' ریال',
        ];
        try {
            DB::beginTransaction();

            $operator_id = OperatorActivity::createActivity($store->user->id, 'STORE_CREDIT_DECREASE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data);
            $trans_id = createstoretransaction::storeTransaction($store, $request->storecredit, false, 3, 0, null, null, $description);
            StoreTransactionDetailsModel::createDetail($trans_id, $trans_data);
            $bank_trans = banktransaction::transaction($bank_id->id, $request->storecredit, true, $trans_id, 'store');
            $store->save();

            DB::commit();
            toastr()->success('کاهش اعتبار فروشگاه با موفقیت انجام شد.');
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            toastr()->error('مشکلی در افزایش اعتبار فروشگاه رخ داده است!' . $e);
            return false;
        }
    }

    // create document view page
    public function createdocument()
    {
        $this->authorize('createcolleague.createdocument');
        $existingRecords = bankTypeModel::whereHas('account')->get();
        $newRecord1 = new bankTypeModel();
        $newRecord1->id = 8;
        $newRecord1->code = 28;
        $newRecord1->name = 'فروشگاه';

        $newRecord2 = new bankTypeModel();
        $newRecord2->id = 9;
        $newRecord2->code = 29;
        $newRecord2->name = 'خریدار';

        // Convert the existing records and the new records to arrays
        $existingRecordsArray = $existingRecords->toArray();
        $newRecordArray1 = $newRecord1->toArray();
        $newRecordArray2 = $newRecord2->toArray();

        // Merge the arrays into a single array
        $type = array_merge($existingRecordsArray, [$newRecordArray1, $newRecordArray2]);


        return view('back.createcolleague.createdocument', compact('type'));
    }

    // storing document store function
    public function createDocumentStore(ColleagueCreateDocument $request)
    {
        $number = 0;
        $data = [
            'debtor' => $request->input('debtor'),
            'creditor' => $request->input('creditor'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'document' => $request->documents,
        ];
        if (($request->debtor_type == 9 || $request->debtor_type == 8) && ($request->creditor_type == 9 || $request->creditor_type == 8)) {
            toastr()->warning('ماهیت بستانکار باید از نوع حساب های داخلی باشد!');
        } else if ($request->debtor_type == $request->creditor_type) {
            toastr()->warning('ماهیت بستانکار و بدهکار نمیتوانند یکی باشند!');
        } else if ($request->creditor_type == 9) {
            // dd($request->documents, $data);
            $number = $this->buyerCreditor($data);
            if ($number != 0) {
                toastr()->success('ایجاد سند جدید با شماره ' . $number . ' با موفقیت ثبت گردید.');
            } else {
                toastr()->warning('متأسفانه عملیات انجام نشد!');
            }
        } else if ($request->debtor_type == 9) {
            $user = user::with('wallet')->find($request->input('debtor'));
            if ($user->wallet->balance >= $request->input('amount')) {
                $number = $this->buyerDebtor($data);
                if ($number != 0) {
                    toastr()->success('ایجاد سند جدید با شماره ' . $number . ' با موفقیت ثبت گردید.');
                } else {
                    toastr()->warning('متأسفانه عملیات انجام نشد!');
                }
            } else {
                toastr()->warning('مبلغ کیف پول کاربر کمتر از مبلغ درخواستی میباشد!');
            }
        } else if ($request->creditor_type == 8) {
            $number = $this->storeCreditor($data);
            if ($number != 0) {
                toastr()->success('ایجاد سند جدید با شماره ' . $number . ' با موفقیت ثبت گردید.');
            } else {
                toastr()->warning('متأسفانه عملیات انجام نشد!');
            }
        } else if ($request->debtor_type == 8) {
            $store = createstore::where('user_id', $request->input('debtor'))->first();
            // dd($store);
            if ($store->salesamount >= $request->input('amount')) {

                $number = $this->storeDebtor($data);
                if ($number != 0) {
                    toastr()->success('ایجاد سند جدید با شماره ' . $number . ' با موفقیت ثبت گردید.');
                } else {
                    toastr()->warning('متأسفانه عملیات انجام نشد!');
                }
            } else {
                toastr()->warning('مبلغ کیف پول فروشگاه کمتر از مبلغ درخواستی میباشد!');
            }
        } else {
            $number = $this->accountDocument($data);
            if ($number != 0) {
                toastr()->success('ایجاد سند جدید با شماره ' . $number . ' با موفقیت ثبت گردید.');
            } else {
                toastr()->warning('متأسفانه عملیات انجام نشد!');
            }
        }
        if ($number != 0) {
            return redirect()->back()->with('number', $number);
        } else {
            return redirect()->back();
        }
    }
    public function buyerCreditor($data)
    {
        $user = User::find($data['creditor']);
        if ($data['document']) {
            $files = $data['document'];
            $paths = [];
            foreach ($files as $file) {
                $imageName = time() . '_DocCreate.' . $file->getClientOriginalExtension();
                $file->move('document/DocCreate/', $imageName);
                $path = '/document/DocCreate/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $number = createdocument::count();
        if ($number > 0 && $number != 0) {
            $number = createdocument::latest()->first()->numberofdocuments + 1;
        } else {
            $number = 10000;
        }
        // Get the wallet for the user
        $wallet = $user->getWallet();
        try {
            DB::beginTransaction();

            $buyerTrans = buyertransaction::transaction($user, $data['amount'], true, '1', '1', 'ایجاد سند مالی');
            $bank = banktransaction::transaction($data['debtor'], $data['amount'], false, $buyerTrans->id, 'user');
            $history = $wallet->histories()->create([
                'type'        => 'deposit',
                'amount'      => $data['amount'],
                'description' => 'شارژ توسط اپراتور',
                'source'      => 'admin',
                'status'      => 'success'
            ]);
            $data1 = [
                'موجودی قبلی' => $wallet->balance . ' ریال',
                'مقدار افزایش موجودی' => $data['amount'] . ' ریال',
            ];
            $wallet->balance += $data['amount'];
            createdocument::create([
                'type'        => 'deposit',
                'transaction_id' => $bank->id,
                'user_id' => $user->id,
                'description' => $data['description'],
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);
            $user->save();
            $wallet->save();
            $operator_id = OperatorActivity::createActivity($user->id, 'CREATE_DOCUMNET_INCREASE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data1);
            DB::commit();
            return $number;
        } catch (\Exception $e) {
            DB::rollBack();
            \log::error($e);
            return 0;
        }
    }

    public function buyerDebtor($data)
    {
        $user = User::with('wallet')->find($data['debtor']);
        if ($data['document']) {
            $files = $data['document'];
            $paths = [];
            foreach ($files as $file) {

                $imageName = time() . '_DocCreate.' . $file->getClientOriginalExtension();
                $file->move('document/DocCreate/', $imageName);
                $path = '/document/DocCreate/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $number = createdocument::count();
        if ($number > 0 && $number != 0) {
            $number = createdocument::latest()->first()->numberofdocuments + 1;
        } else {
            $number = 10000;
        }

        // Get the wallet for the user
        $wallet = $user->getWallet();
        try {
            DB::beginTransaction();

            $buyerTrans = buyertransaction::transaction($user, $data['amount'], false, '1', '1', 'ایجاد سند مالی');
            $bank = banktransaction::transaction($data['creditor'], $data['amount'], true, $buyerTrans->id, 'user');

            $history = $wallet->histories()->create([
                'type'        => 'withdraw',
                'amount'      => $data['amount'],
                'description' => 'شارژ توسط اپراتور',
                'source'      => 'admin',
                'status'      => 'success'
            ]);
            $data1 = [
                'موجودی قبلی' => $wallet->balance . ' ریال',
                'مقدار کاهش موجودی' => $data['amount'] . ' ریال',
            ];
            $wallet->balance -= $data['amount'];
            createdocument::create([
                'type'        => 'withdraw',
                'transaction_id' => $bank->id,
                'user_id' => $user->id,
                'description' => $data['description'],
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);
            $user->save();
            $wallet->save();
            $operator_id = OperatorActivity::createActivity($user->id, 'CREATE_DOCUMNET_DECREASE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data1);
            DB::commit();
            return $number;
        } catch (\Exception $e) {
            DB::rollBack();
            \log::error($e);
            return 0;
        }
    }
    public function storeCreditor($data)
    {
        // dd($data);
        $store = createstore::where('user_id', $data['creditor'])->first();
        // dd($store);
        if ($data['document']) {
            $files = $data['document'];
            $paths = [];
            foreach ($files as $file) {

                $imageName = time() . '_DocCreate.' . $file->getClientOriginalExtension();
                $file->move('document/DocCreate/', $imageName);
                $path = '/document/DocCreate/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $number = StoreDoumentModel::count();
        if ($number > 0 && $number != 0) {
            $number = StoreDoumentModel::latest()->first()->numberofdocuments + 1;
        } else {
            $number = 10000;
        }

        $trans_data = [
            'تراکنش:' => 'افزایش موجودی کیف پول فروشگاه',
            'اسم فروشگاه:' => $store->nameofstore,
            'توسط:' => Auth::user()->username,
            'مبلغ تراکنش:' => number_format($data['amount']) . 'ریال',
            'موجودی قبلی:' => number_format($store->salesamount) . 'ریال',
            'موجودی فعلی:' => number_format($store->salesamount + $data['amount']) . 'ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        // Get the wallet for the user

        try {
            DB::beginTransaction();

            $store_trans = createstoretransaction::storeTransaction($store, $data['amount'], true, 0, 1, $store->user_id, null, 'ایجاد سند مالی فروشگاه');
            $bank_trans = banktransaction::transaction($data['debtor'], $data['amount'], false, $store_trans, 'store');
            StoreTransactionDetailsModel::createDetail($store_trans, $trans_data);

            $data1 = [
                'موجودی قبلی' => $store->salesamount . ' ریال',
                'مقدار افزایش موجودی' => $data['amount'] . ' ریال',
            ];
            $store->salesamount += $data['amount'];

            StoreDoumentModel::create([
                'type'          => 'deposit',
                'transaction_id' => $bank_trans->id,
                'store_id' => $store->id,
                'description' => $data['description'],
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);
            $store->save();

            $operator_id = OperatorActivity::createActivity($store->user_id, 'STORE_DOCUMENTـICREASE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data1);
            DB::commit();
            return $number;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return 0;
        }
    }
    public function storeDebtor($data)
    {
        $store = createstore::where('user_id', $data['debtor'])->first();
        if ($data['document']) {
            $files = $data['document'];
            $paths = [];
            foreach ($files as $file) {

                $imageName = time() . '_DocCreate.' . $file->getClientOriginalExtension();
                $file->move('document/DocCreate/', $imageName);
                $path = '/document/DocCreate/' . $imageName;
                $paths[] = $path;
            }
            $docPath = json_encode($paths);
        }
        $number = StoreDoumentModel::count();
        if ($number > 0 && $number != 0) {
            $number = StoreDoumentModel::latest()->first()->numberofdocuments + 1;
        } else {
            $number = 10000;
        }

        $trans_data = [
            'تراکنش:' => 'کاهش موجودی کیف پول فروشگاه',
            'اسم فروشگاه:' => $store->nameofstore,
            'توسط:' => Auth::user()->username,
            'مبلغ تراکنش:' => number_format($data['amount']) . 'ریال',
            'موجودی قبلی:' => number_format($store->salesamount) . 'ریال',
            'موجودی فعلی:' => number_format($store->salesamount - $data['amount']) . 'ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];

        // Get the wallet for the user

        try {
            DB::beginTransaction();

            $store_trans = createstoretransaction::storeTransaction($store, $data['amount'], false, 0, 1, $store->user_id, null, 'ایجاد سند مالی فروشگاه');
            $bank_trans = banktransaction::transaction($data['creditor'], $data['amount'], true, $store_trans, 'store');
            StoreTransactionDetailsModel::createDetail($store_trans, $trans_data);

            $store->salesamount -= $data['amount'];

            $data1 = [
                'موجودی قبلی' => $store->salesamount . ' ریال',
                'مقدار کاهش موجودی' => $data['amount'] . ' ریال',
            ];

            StoreDoumentModel::create([
                'type'          => 'withdraw',
                'transaction_id' => $bank_trans->id,
                'store_id' => $store->id,
                'description' => $data['description'],
                'documents' => $docPath,
                'numberofdocuments' => $number,
            ]);
            $store->save();

            $operator_id = OperatorActivity::createActivity($store->user_id, 'STORE_DOCUMENTـDECREASE');
            ActivityDetailsModel::createActivityDetail($operator_id, $data1);
            DB::commit();
            return $number;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return 0;
        }
    }
    public function accountDocument($data)
    {
        try {
            DB::beginTransaction();
            $debtor_trans = banktransaction::transaction($data['debtor'], $data['amount'], false, null, null);
            $creditor_trans = banktransaction::transaction($data['creditor'], $data['amount'], true, null, null);

            $data1 = [
                'حساب بدهکار' => BankAccount::find($data['debtor'])->bankname,
                'حساب بستانکار' => BankAccount::find($data['creditor'])->bankname,
                'مبلغ سند' => $data['amount'] . ' ریال',
            ];

            if ($data['document']) {
                $files = $data['document'];
                $paths = [];
                foreach ($files as $file) {

                    $imageName = time() . '_DocCreate.' . $file->getClientOriginalExtension();
                    $file->move('document/DocCreate/', $imageName);
                    $path = '/document/DocCreate/' . $imageName;
                    $paths[] = $path;
                }
                $docPath = json_encode($paths);
            }

            $number = AccountDocumentModel::count();
            if ($number > 0 && $number != 0) {
                $number = AccountDocumentModel::latest()->first()->numberofdocuments + 1;
            } else {
                $number = 10000;
            }
            $operator_id = OperatorActivity::createActivity(null, 'BANK_DOCUMENT_TRANS');
            ActivityDetailsModel::createActivityDetail($operator_id, $data1);
            // ['debtor_trans_id', 'creditor_trans_id', 'documents', 'type', 'numberofdocuments', 'description'];
            AccountDocumentModel::create([
                'debtor_trans_id'    => $debtor_trans->id,
                'creditor_trans_id'  => $creditor_trans->id,
                'documents'          => $docPath,
                'numberofdocuments'  => $number,
                'description'        =>  $data['description'],
            ]);

            DB::commit();
            return $number;
        } catch (\Exception $e) {
            DB::rollBack();
            return 0;
        }
    }
    public function accountList(Request $request)
    {
        $query = $request->input('id');
        $account = BankAccount::where('account_type_id', $query)->get(['id', 'bankname']);
        return response()->json($account);
    }
}
