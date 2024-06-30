<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Sms;
use App\Models\Viewer;
use App\Traits\OrderStatisticsTrait;
use App\Traits\UserStatisticsTrait;
use App\Traits\ViewStatisticsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StatisticsController extends Controller
{
    use OrderStatisticsTrait, UserStatisticsTrait, ViewStatisticsTrait;

    public function viewsList()
    {
        $this->authorize('statistics.viewsList');

        $views = Viewer::latest();

        if (auth()->user()->level != 'creator') {
            $views = $views->whereNull('user_id')->orWhere(function ($query) {
                $query->whereHas('user', function ($q1) {
                    $q1->where('level', '!=', 'creator');
                });
            });
        }

        $views = $views->paginate(20);

        return view('back.statistics.views.viewsList', compact('views'));
    }

    public function views()
    {
        $this->authorize('statistics.views');

        return view('back.statistics.views.index');
    }

    public function viewers()
    {
        $this->authorize('statistics.viewers');

        $viewers = Viewer::latest()->whereDate('created_at', now())->get()->unique('user_id');

        return view('back.statistics.viewers.viewers', compact('viewers'));
    }

    public function orders()
    {
        $this->authorize('statistics.orders');

        return view('back.statistics.orders.index');
    }

    public function users()
    {
        $this->authorize('statistics.users');

        return view('back.statistics.users.index');
    }

    public function smsLog()
    {
        $this->authorize('statistics.sms');

        // $sms = Sms::latest()->paginate(20);

        return view('back.statistics.sms.sms-log');
    }
    public function smsLogData()
    {
        $queries = Sms::orderBy('created_at', 'desc')->select('mobile', 'type', 'created_at', 'id')->get();

        if ($queries->count() === 0) {
            return DataTables::collection([])->make(true);
        }

        $data = collect($queries)->map(function ($query) {
            return [
                'counter' => null,
                'mobile' => $query->mobile,
                'type' => $query->type(),
                'time' => $query->created_at->ago(),
                'action' => '<button type="button" data-action="' . route('admin.sms.show', ['sms' => $query->id]) . '" class="btn btn-info waves-effect waves-light show-sms btn-sm">بیشتر<i class="feather icon-info"></i></button>',
            ];
        });

        return DataTables::collection($data)->make(true);
    }
}
