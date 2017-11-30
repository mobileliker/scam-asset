<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1）添加权限控制；（2017/7/5）
 * （2）Index函数添加农具的相关统计数据；（2017/9/30）
 * （3）Index函数添加岩石相关的统计数据；（2017/11/1）
 * （4）优化了Index的统计功能，精简了代码：（2017/11/24）
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/29
 * @description :
 * (1)添加段面土壤标本和纸盒标本统计；（2017/11/29）
 * (2)添加动物标本的统计；（2017/11/30）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User, App\Alog, App\Asset;
use App\Farm;
use App\CollectionImage;
use App\Rock;
use App\Plant;
use ReflectionClass;
use Log;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:Common|Method-Common-Index,true')->only('index');
    }

    public function index()
    {
        $total = array();

        $year = date('Y');
        $month = date('m');
        $userTotal = User::count();
        $alogTotal = Alog::count();

        $assetNumber = Asset::count();
        $assetSum = Asset::sum('sum');
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
            ],
        ];

        $prefixs = array();
        $prefixs[] = 'farm';
        $prefixs[] = 'rock';
        $prefixs[] = 'plant';
        $prefixs[] = 'animal';
        $prefixs[] = 'soilBig';
        $prefixs[] = 'soilSmall';

        $numberSum = 0;
        $imageNumberSum = 0;
        $monthAddSum = 0;
        $yearAddSum = 0;
        $imageMonthAddSum = 0;
        $imageYearAddSum = 0;
        foreach ($prefixs as $key => $prefix) {
            $model_name = 'App\\' . ucfirst($prefix);
            $class = new ReflectionClass($model_name);
            $model = $class->newInstanceArgs();

            $number = $model->count();
            $imageNumber = CollectionImage::where('collectible_type', $model_name)->count();
            $monthAdd = $model->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $yearAdd = $model->whereYear('created_at', $year)->count();
            $imageMonthAdd = CollectionImage::where('collectible_type', $model_name)->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $imageYearAdd = CollectionImage::where('collectible_type', $model_name)->whereYear('created_at', $year)->count();

            $numberSum += $number;
            $imageNumberSum += $imageNumber;
            $monthAddSum += $monthAdd;
            $yearAddSum += $yearAdd;
            $imageMonthAddSum += $imageMonthAdd;
            $imageYearAddSum += $imageYearAdd;

            $total[$prefix] = [
                'number' => $number,
                'image_number' => $imageNumber,
                'month_add' => $monthAdd,
                'year_add' => $yearAdd,
                'image_month_add' => $imageMonthAdd,
                'image_year_add' => $imageYearAdd,
            ];
        }

        $total['collection'] = [
            'number' => $numberSum,
            'image_number' => $imageNumberSum,
            'month_add' => $monthAddSum,
            'year_add' => $yearAddSum,
            'image_month_add' => $imageMonthAddSum,
            'image_year_add' => $imageYearAddSum,
        ];

        return response()->json($total);
    }
}
