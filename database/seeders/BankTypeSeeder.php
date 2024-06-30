<?php

namespace Database\Seeders;

use App\Models\bankTypeModel;
use Illuminate\Database\Seeder;

class BankTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['بانک',  'هزینه', 'درآمد', 'واسط قسط ها', 'واسط اعتبار فروشگاه ها', 'واسط اعتبار  خریدارها', 'واسط پیش پرداخت'];
        // $codes = [21,22,23,24,25,26];
        $code = 21;
        foreach ($names as $name) {
            bankTypeModel::create([
                "name" => $name,
                "code" => $code++

            ]);
        }
    }
}
