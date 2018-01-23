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
 * （3）更改权限控制；（2017/12/14）
 *
 * @version ： 2.0.3
 * @author ： wuzhihui
 * @date : 2018/1/11
 * @description :
 * (1)新增首页的藏品入库统计功能；（2018/1/11）
 * (2)新增首页的固定资产入库统计功能；(2018/1/23)
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
//        $this->middleware('ability:Common|Method-Common-Index,true')->only('index');
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

        $years = array();
        $years[] = 2015;
        $years[] = 2016;
        $years[] = 2017;
        $years[] = 2018;
        $countYears =

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

        $yearCounts = array();

        $sumCount = array();
        $sumCount['year'] = '合计';
        $sumCount['sum'] = 0;

        foreach ($years as $key => $year) {
            $yearCount = array();
            $yearCount['year'] = $year;
            $yearCount['sum'] = 0;

            foreach ($prefixs as $key => $prefix) {
                $model_name = 'App\\' . ucfirst($prefix);
                $class = new ReflectionClass($model_name);
                $model = $class->newInstanceArgs();

                if ($prefix == 'soilBig' || $prefix == 'soilSmall') {
                    $count = $model->join('soils', 'soils.id', '=', 'soil_id')->whereYear('soils.input_date', '=', $year)->count();
                } else {
                    $count = $model->whereYear('input_date', '=', $year)->count();
                }
                $yearCount['sum'] = $yearCount['sum'] + $count;
                $yearCount[$prefix] = $count;

                if (isset($sumCount[$prefix])) $sumCount[$prefix] = $sumCount[$prefix] + $count;
                else $sumCount[$prefix] = $count;
                $sumCount['sum'] = $sumCount['sum'] + $count;
            }
            $yearCounts[] = $yearCount;
        }
        $yearCounts[] = $sumCount;
        $total['yearCounts'] = $yearCounts;

        //固定资产统计
        $assetArr = array();

        foreach ($years as $key => $year) {
            $assetCount = array();
            $assetCount['year'] = $year;
            $data = Asset::whereYear('post_date', '=', $year)->groupBy('type')->selectRaw("type, sum(sum) as sum, count(id) as count")->get();
            foreach (Asset::TYPE as $key => $value) {
                $assetCount[$key] = '-';
            }
            $sumSum = 0;
            $sumCount = 0;
            foreach ($data as $key => $value) {
                $assetCount[$value->type] = $value->count . '件(' . $value->sum . '元)';
                $sumSum = $sumSum + $value->sum;
                $sumCount = $sumCount + $value->count;
            }
            $assetCount['sum'] = $sumCount . '件(' . $sumSum . '元)';
            $assetArr[] = $assetCount;
        }

        $sumAsset = array();
        $sumAsset['year'] = '合计';
        foreach (Asset::TYPE as $key => $type) {
            $sumAsset[$key] = '-';
        }
        $data = Asset::groupBy('type')->selectRaw('type, sum(sum) as sum, count(id) as count')->get();
        $sumSum = 0;
        $sumCount = 0;
        foreach ($data as $key => $value) {
            $sumAsset[$value->type] = $value->count . '件(' . $value->sum . '元)';
            $sumSum = $sumSum + $value->sum;
            $sumCount = $sumCount + $value->count;
        }
        $sumAsset['sum'] = $sumCount . '件(' . $sumSum . '元)';
        $assetArr[] = $sumAsset;
        $total['assetCount'] = $assetArr;

        return response()->json($total);
    }
}
