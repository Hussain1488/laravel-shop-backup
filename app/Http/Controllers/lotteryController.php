<?php

namespace App\Http\Controllers;

use App\Exports\DailyCodeExport;
use App\Http\Requests\Back\lottery\LotteryCodeStateRequest;
use App\Http\Requests\Back\lottery\LotteryCodeWonRequest;
use App\Models\DailyCodeModel;
use App\Models\InvoicesModel;
use App\Models\LotteryCodeModel;
use App\Models\LotteryWinnersModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use Shetabit\Multipay\Invoice;
use Yajra\DataTables\Facades\DataTables;

class lotteryController extends Controller
{
    //
    public function index()
    {
        $this->authorize('lottery.index');
        return view('back.lottery.index');
    }

    public function lotteryCodeNumbers(Request $request)
    {
        $query = LotteryCodeModel::latest();

        if ($request->filter == 'invoice') {
            $query = LotteryCodeModel::where('invoice_id', '!=', null)->latest();
        } else if ($request->filter == 'dailyCode') {
            $query = LotteryCodeModel::where('daily_code', '!=', null)->latest();
        } else if ($request->filter == 'active') {
            $query = LotteryCodeModel::where('state', 'active')->latest();
        } else if ($request->filter == 'deactive') {
            $query = LotteryCodeModel::where('state', 'deactive')->latest();
        }


        return DataTables::eloquent($query)
            ->addColumn('counter', function () {
                return null;
            })
            ->addColumn('user_id', function ($query) {
                return $query->user->username;
            })
            ->addColumn('code', function ($query) {
                return $query->code;
            })
            ->filterColumn('code', function ($query, $keyword) {
                return $query->where('code', 'like', $keyword . '%');
            })
            ->addColumn('source', function ($query) {
                return $query->invoice_id ? 'invoice' : 'daily_code';
            })
            ->addColumn('state', function ($query) {
                return $query->state;
            })
            ->addColumn('date', function ($query) {
                return jDate($query->created_at)->format('Y-m-d');
            })
            ->addColumn('button', function ($query) {
                return ['id' => $query->id, 'state' => $query->winner != null ? false : true];
            })->addColumn('unused_column', function () {
                return ''; // Provide dummy data for unused column
            })
            ->make(true);
    }


    public function invoicesIndex()
    {
        $this->authorize('lottery.invoicesIndex');
        return view('back.lottery.invoiceIndex');
    }

    public function invoicesDatatable(Request $request)
    {
        if ($request->filter != 'all') {
            $query = InvoicesModel::where('state', $request->filter)->latest();
        } else {

            $query = InvoicesModel::orderByRaw("
            CASE
            WHEN state = 'pending' THEN 1
            WHEN state = 'valid' THEN 2
            WHEN state = 'not-valid' THEN 3
            ELSE 4
            END")->latest();
        }

        return DataTables::eloquent($query)
            ->addColumn('counter', function () {
                return null;
            })->addColumn('date', function ($query) {
                return jdate($query->created_at)->format('Y-m-d');
            })->addColumn('number', function ($query) {
                return $query->number;
            })->filterColumn('number', function ($query, $keyword) {
                $query->where('number', 'like', $keyword . '%');
            })
            ->addColumn('amount', function ($query) {
                return $query->amount;
            })->filterColumn('amount', function ($query, $keyword) {
                $query->where('amount', 'like', $keyword . '%');
            })->addColumn('image', function ($query) {
                return asset($query->image);
            })
            ->addColumn('state', function ($query) {
                return $query->state;
            })->addColumn('action', function ($query) {
                return $query->id;
            })->make(true);
    }
    public function invoiceRejection($id = null)
    {

        // return response()->json(['status' => 'error', 'data' => 'انجام عملیات با خطا روبه رو شد!']);
        try {
            $invoice = InvoicesModel::findOrFail($id);
            $invoice->state = 'not-valid';
            $invoice->save();
            return response()->json(['status' => 'success', 'data' => 'عملیات با موفقیت انجام شد!']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'error', 'data' => 'انجام عملیات با خطا روبه رو شد!']);
        }
    }

    public function invoiceValidation(Request $request, $id)
    {
        // dd($request, $id);
        try {
            $invoice = InvoicesModel::find($id);

            for ($i = 0; $i < $request->lottery_code_number; $i++) {
                $code = $this->lotteryCodeGenarator();
                $invoice->lotteryCode()->create([
                    'user_id' => $invoice->user_id,
                    'code' => $code,
                    'weekly_state' => false,
                    'monthly_state' => false,
                    'state' => 'active',
                ]);
            }
            $invoice->state = 'valid';
            $invoice->save();
            return response()->json(['status' => 'success', 'data' => 'عملیات با موفقیت انجام شده و کدهای قرعه کشی برای کاربر ایجاد شد!']);
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['status' => 'error', 'data' => $e]);
        }
    }
    public function dailyCode()
    {
        $this->authorize('lottery.dailyCode');
        $lastValue = DailyCodeModel::orderBy('date', 'desc')->first();
        if (!$lastValue) {
            $lastValue = null;
        } else {
            $lastValue = $lastValue->date;
        }
        // dd($lastValue);
        // dd($lastValue);
        return view('back.lottery.dailyCode', compact('lastValue'));
    }

    public function dailyCodeDatatable(Request $request)
    {
        $this->authorize('lottery.dailyCode');
        $filter = $request->input('filter');
        if ($filter == 'weekly') {
            Carbon::setWeekStartsAt(Carbon::SATURDAY);

            // Set Friday as the end of the week
            Carbon::setWeekEndsAt(Carbon::FRIDAY);

            // Get the start and end dates of the current week
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query = DailyCodeModel::whereBetween('date', [$startOfWeek, $endOfWeek]);
        } else if ($filter == 'all') {
            $query = DailyCodeModel::query();
        } else if ($filter == 'today') {
            $today = Carbon::now()->format('Y-m-d');
            $query = DailyCodeModel::where('date', $today);
        } else {
            $jalalianNow = Jalalian::now();
            // Get the year and month components of the current Persian date
            $year = $jalalianNow->getYear();
            $month = $jalalianNow->format('m');
            // Get the first day of the current Persian month
            $firstDayOfMonth = Jalalian::fromFormat('Y-m-d', $year . '-' . $month . '-01');
            // Get the last day of the current Persian month
            $lastDayOfMonth = $firstDayOfMonth->addMonths(1)->subDays(1);
            $firstDay = $firstDayOfMonth->toCarbon();
            $lastDay = $lastDayOfMonth->toCarbon();
            $query = DailyCodeModel::whereBetween('date', [$firstDay, $lastDay]);
        }

        return DataTables::eloquent($query)
            ->addColumn('counter', function () {
                return null;
            })
            ->addColumn('date', function ($query) {
                return [
                    'date' => jDate($query->date)->format('Y-m-d'),
                    'is_today' => Carbon::parse($query->date)->isToday(),
                ];
            })
            ->addColumn('insta', function ($query) {
                return $query->insta;
            })->filterColumn('insta', function ($query, $keyword) {
                $query->where('insta', 'like', $keyword . '%');
            })
            ->addColumn('rubika', function ($query) {
                return $query->rubika;
            })->filterColumn('rubika', function ($query, $keyword) {
                $query->where('rubika', 'like', $keyword . '%');
            })
            ->addColumn('site', function ($query) {
                return $query->site;
            })->filterColumn('site', function ($query, $keyword) {
                $query->where('site', 'like', $keyword . '%');
            })
            ->addColumn('eitaa', function ($query) {
                return $query->eitaa;
            })->filterColumn('eitaa', function ($query, $keyword) {
                $query->where('eitaa', 'like', $keyword . '%');
            })
            ->make(true);
    }
    public function generateCode()
    {
        // Get the first day of the current month in Jalali format
        $today = Jalalian::now()->getFirstDayOfMonth();

        // Convert Jalali date to Carbon date and format it as 'Y-m-d'
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $today->toCarbon()->toDateTimeString());

        // Check if the date exists in the database
        $dateExists = DailyCodeModel::where('date', $carbonDate->format('Y-m-d'))->exists();

        // If the date exists, move to the next month
        if (!$dateExists) {
            $nextMonth = $carbonDate;
            $nextMonth1 = $today;
        } else {
            $nextMonth = $carbonDate->addMonths(1);
            $nextMonth1 = $today->addMonths(1);
        }
        // Get the number of days in the current month
        // $numDaysInMonth = $nextMonth1->subdays()->format('d') + 1;
        $nextMonthFirstDay = $nextMonth1->addMonths(1)->getFirstDayOfMonth();

        // Move back one day to get the last day of the current month
        $lastDayOfMonth = $nextMonthFirstDay->subDays(1);

        // Get the day part of the date (e.g., '01' for the first day, '02' for the second day, etc.)
        $lastDayOfMonthDayPart = $lastDayOfMonth->format('d');

        // Convert it to an integer to get the number of days
        $numDaysInMonth = (int) $lastDayOfMonthDayPart;

        $monthBefore = Jalalian::now()->subMonths(1)->getFirstDayOfMonth();
        $monthBefore = Carbon::createFromFormat('Y-m-d H:i:s', $monthBefore->toCarbon()->toDateTimeString());
        // Loop through each day of the month
        $monthBeforeCodes = DailyCodeModel::where('date', '<', $monthBefore)->get();

        foreach ($monthBeforeCodes as $code) {
            $code->delete();
        }

        for ($day = 1; $day <= $numDaysInMonth; $day++) {
            // Create Jalalian date for each day


            $rubika = $this->CodeGerator('rubika');
            $site = $this->CodeGerator('site');
            $insta = $this->CodeGerator('insta');
            $eitaa = $this->CodeGerator('eitaa');
            $DailyCode = [
                'rubika' => $rubika,
                'site' => $site,
                'insta' => $insta,
                'eitaa' => $eitaa,
                'date' => $carbonDate->format('Y-m-d'),
            ];
            $carbonDate = $carbonDate->addDay();


            DailyCodeModel::create($DailyCode);
        }
        return response('success');
    }


    public function CodeGerator($source)
    {
        $code = rand(111111, 999999);
        $exists = DailyCodeModel::where('insta', $code)
            ->orWhere('rubika', $code)
            ->orWhere('site', $code)
            ->orWhere('eitaa', $code)
            ->exists();

        if ($exists) {
            $this->CodeGerator($source);
        } else {
            return $code;
        }
    }
    public function lotteryCodeGenarator()
    {
        $code = rand(100000, 999999);
        if (LotteryCodeModel::where('code', $code)->exists()) {
            $this->codeGenarator();
        }

        return $code;
    }


    public function dailyCodePrint()
    {
        // dd('hey');
        return Excel::download(new DailyCodeExport(DailyCodeModel::get()), 'dailyCode.xlsx');
    }

    public function codeWonState(LotteryCodeWonRequest $request)
    {
        // dd($request->all());
        try {
            $lotteryCode = LotteryCodeModel::find($request->id);
            // Log::error($lotteryCode);
            LotteryWinnersModel::create([
                'user_id' => $lotteryCode->user->id,
                'lottery_code_id' => $lotteryCode->id,
                'type' => $request->type,
                'state' => 'not-paid',
                'description' => $request->description,
                'lottery_date' => Carbon::now()->format('Y-m-d'),
            ]);
            return response()->json(['status' => 'success', 'message' => 'عملیات با موفقیت انجام شد!']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'fail', 'message' => 'مشکلی در انجام عملیات رخ داده است!']);
        }
    }
    public function CodeState(LotteryCodeStateRequest $request)
    {
        // dd($request->all());
        try {
            $lotteryCode = LotteryCodeModel::find($request->id);
            $lotteryCode->state = $request->state;
            $lotteryCode->save();
            return response()->json(['status' => 'success', 'message' => 'عملیات با موفقیت انجام شد!']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['status' => 'fail', 'message' => 'مشکلی در انجام عملیات رخ داده است!']);
        }
    }
    // public function dailyCodeTest()
    // {
    //     $today = Jalalian::now()->getFirstDayOfMonth();
    //     $today = $today->subMonths(1)->getFirstDayOfMonth();

    //     // Convert Jalali date to Carbon date and format it as 'Y-m-d'
    //     $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $today->toCarbon()->toDateTimeString());
    //     $nextMonth1 = $today;
    //     $nextMonthFirstDay = $nextMonth1->subMonths(2)->getFirstDayOfMonth();

    //     // Move back one day to get the last day of the current month
    //     $lastDayOfMonth = $nextMonthFirstDay->subDays(1);

    //     // Get the day part of the date (e.g., '01' for the first day, '02' for the second day, etc.)
    //     $lastDayOfMonthDayPart = $lastDayOfMonth->format('d');

    //     // Convert it to an integer to get the number of days
    //     $numDaysInMonth = (int) $lastDayOfMonthDayPart;
    //     for ($day = 1; $day <= $numDaysInMonth; $day++) {
    //         // Create Jalalian date for each day


    //         $rubika = $this->CodeGerator('rubika');
    //         $site = $this->CodeGerator('site');
    //         $insta = $this->CodeGerator('insta');
    //         $eitaa = $this->CodeGerator('eitaa');
    //         $DailyCode = [
    //             'rubika' => $rubika,
    //             'site' => $site,
    //             'insta' => $insta,
    //             'eitaa' => $eitaa,
    //             'date' => $carbonDate->format('Y-m-d'),
    //         ];
    //         $carbonDate = $carbonDate->addDay();


    //         DailyCodeModel::create($DailyCode);
    //     }
    // }
    public function winners()
    {
        $this->authorize('lottery.winners');
        return view('back.lottery.winners');
    }
    public function winnerData(Request $request)
    {

        if ($request->filter == 'weekly') {
            $query = LotteryWinnersModel::where('type', 'weekly')->latest();
        } else if ($request->filter == 'monthly') {
            $query = LotteryWinnersModel::where('type', 'monthly')->latest();
        } else if ($request->filter == 'yearly') {
            $query = LotteryWinnersModel::where('type', 'yearly')->latest();
        } else {
            $query = LotteryWinnersModel::latest();
        }


        return DataTables::eloquent($query)
            ->addColumn('counter', function () {
                return null;
            })->addColumn('user_id', function ($query) {
                return $query->user->first_name . ' ' . $query->user->last_name;
            })->addColumn('lottery_code', function ($query) {
                return $query->latteryCode->code;
            })->addColumn('type', function ($query) {
                return $query->type;
            })->addColumn('description', function ($query) {
                return $query->description;
            })->addColumn('lottery_date', function ($query) {
                return jDate($query->lottery_date)->format('Y-m-d');
            })->make(true);
    }
}
