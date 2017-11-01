<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1）添加权限控制；（2017/7/5）
 * （2）Index函数添加农具的相关统计数据；（2017/9/30）
 * （3）Index函数添加岩石相关的统计数据；（2017/11/1）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User, App\Alog, App\Asset;
use App\Farm;
use App\CollectionImage;
use App\Rock;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:Common|Method-Common-Index,true')->only('index');
    }

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

        $year = date('Y');
        $month = date('m');
        $userTotal = User::count();
        $alogTotal = Alog::count();


        $assetNumber = Asset::count();
        $assetSum = Asset::sum('sum');
        $assetMonthAdd = Asset::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $assetYearAdd = Asset::whereYear('created_at', $year)->count();

        $farmNumber = Farm::count();
        $farmMonthAdd = Farm::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $farmYearAdd = Farm::whereYear('created_at', $year)->count();
        $farmImageMonthAdd = CollectionImage::where('collectible_type', Farm::class)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $farmImageYearAdd = CollectionImage::where('collectible_type', Farm::class)->whereYear('created_at', $year)->count();

        $rockNumber = Rock::count();
        $rockMonthAdd = Rock::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $rockYearAdd = Rock::whereYear('created_at', $year)->count();
        $rockImageMonthAdd = CollectionImage::where('collectible_type', Rock::class)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $rockImageYearAdd = CollectionImage::where('collectible_type', Rock::class)->whereYear('created_at', $year)->count();


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
            ],
            'farm' => [
                'number' => $farmNumber,
                'month_add' => $farmMonthAdd,
                'year_add' => $farmYearAdd,
                'image_month_add' => $farmImageMonthAdd,
                'image_year_add' => $farmImageYearAdd,
            ],
            'rock' => [
                'number' => $rockNumber,
                'month_add' => $rockMonthAdd,
                'year_add' => $rockYearAdd,
                'image_month_add' => $rockImageMonthAdd,
                'image_year_add' => $rockImageYearAdd,
            ]
        ];
        return response()->json($total);
    }
}
