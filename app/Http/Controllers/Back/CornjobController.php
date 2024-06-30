<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\CornJobStoreRequest;
use App\Models\CornjobModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CornjobController extends Controller
{
    public function index()
    {
        $this->authorize('cornjob.index');
        $cornjobs = CornjobModel::latest()->get();
        return view('back.cornjob.index', compact('cornjobs'));
    }
    public function create()
    {
        $this->authorize('cornjob.setting');
        return view('back.cornjob.setting');
    }
    public function store(CornJobStoreRequest $request)
    {
        $this->authorize('cornjob.setting');
        // dd($request);
        try {
            DB::beginTransaction();
            $this->authorize('cornjob');

            $except = [
                'store_reccredition_status',
                'message_send_befor_status',
                'message_send_after_status',
            ];

            $cornjob = $request->except($except);

            foreach ($cornjob as $key => $value) {
                option_update($key, $value);
            }

            foreach ($except as $option) {
                if ($request->$option) {
                    option_update($option, 'on');
                } else {
                    option_update($option, 'off');
                }
            }
            DB::commit();
            return response('success');
        } catch (\Exception $e) {
            DB::rollBack();
            \log::error($e);
            return response('warning');
        }
        // return redirect()->back();
    }
}
