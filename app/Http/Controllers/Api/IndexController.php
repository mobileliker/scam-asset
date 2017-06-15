<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User, App\Alog, App\Asset;

class IndexController extends Controller
{
    //
    public function index()
    {

//        system : {
//        user_total : '50',
//                        alog_total : '200',
//                    },
//        asset : {
//        number : '200',
//                        sum : '2000',
//                        month_add : '10',
//                        year_add : 500
//                    }

        $userTotal = User::count();
        $alogTotal = Alog::count();
        $assetNumber = Asset::count();
        $assetSum = Asset::sum('sum');

        $year = date('Y');
        $month = date('m');
        $assetMonthAdd = Asset::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $assetYearAdd = Asset::whereYear('created_at', $year)->count();
        $total = [
          'system' => [
              'user_total' => $userTotal,
              'alog_total' => $alogTotal,
          ],
            'asset' => [
                'number' => $assetNumber,
                'sum' => $assetSum,
                'month_add' => $assetMonthAdd,
                'year_add' => $assetYearAdd
            ]
        ];
        return response()->json($total);
    }
}
