<?php

/**
 * Farm表的填充器
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/6
 * @description;
 */

use Illuminate\Database\Seeder;

//Seeder里面无法直接使用别名，因此需要写全路径
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Farm, App\User;

class FarmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        $path = storage_path('input/farm/input.xlsx');
        //Log::info($path);

        Excel::load($path, function ($reader) use ($user){
            $sheet = $reader->getSheet(0);
            $sheet_array = $sheet->toArray();
            foreach ($sheet_array as $row => $cells) {
                if ($row == 0) continue; //忽略标题行
                if($cells[8] == '') continue; //编号不存在则忽略
//                foreach($cells as $col => $cell){
//                    Log::info($cell);
//                }
                $category = $cells[1];
                $name = $cells[2];
                $number = $cells[3];
                $input_date = str_replace('.', '-', $cells[4]);
                $source = $cells[5];
                $description = $cells[6];
                $size = $cells[7];
                $serial = $cells[8];
                $memo = $cells[11];
                $display = $cells[12];
                if ($number > 1) $serial = trim(explode('-', $serial)[0]);
//                Log::info(
//                    'row : ' . $row . ','
//                    . 'category : ' . $category . ','
//                    . 'name : ' . $name . ','
//                    . 'number : ' . $number . ','
//                    . 'input_date : ' . $input_date . ','
//                    . 'source : ' . $source . ','
//                    . 'description : ' . $description . ','
//                    . 'size : ' . $size . ','
//                    . 'serial : ' . $serial . ','
//                    . 'memo : ' . $memo . ','
//                    . 'display : ' . $display
//                );

                while($number--){
                    $farm = Farm::firstOrNew(['serial' => $serial]);
                    $farm->category = $category;
                    $farm->name = $name;
                    $farm->input_date = $input_date;
                    $farm->source = $source;
                    $farm->description = $description;
                    $farm->size = $size;
                    $farm->memo = $memo;
                    $farm->display = $display;
                    $farm->keeper_id = $user->id;
                    $farm->user_id = $user->id;
                    $farm->save();
                    Log::info($farm);

                    $serial = 'A' . (intval(substr($serial, 1)) + 1);
                }

            }
        });

    }
}
