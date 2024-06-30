<?php

namespace App\Exports;

use App\Models\DailyCodeModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DailyCodeExport implements FromView
{
    public $codes;

    public function __construct($codes)
    {
        $this->codes   = $codes;
    }

    public function view(): View
    {
        return view('back.exports.lotteryCode', [
            'codes'     => $this->codes,
        ]);
    }
}
