<?php

/**
 * 导入图片命令行工具
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/11/1
 * @description:
 * （1）基本功能；
 * （2）修改以适应多类型图片；（2017/11/1）
 * （3）新增导入图片的日志记录功能；（2017/12/6）
 * （4）引入土壤管理的图片导入支持；（2017/12/12）
 * （5）修复图片导入的提示语的错误；（2017/12/12）
 * （6）新增图片导入不成功的分类功能；（2017/12/12）
 *（7）新增对大写后缀和jpg格式的图片的支持；（2017/12/12）
 *
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2017/12/18
 * @description:
 * （1）添加图片标签的导入；（2017/12/18）
 * （2）修复农具被删除依然会被查询到的错误；（2018/3/1）
 * （3）新增对植物图片的导入支持；（2018/3/13）
 * （4）新增序列号不存在的命令行提示；（2018/3/14）
 * （5）修改图片已被导入过后的提示语；（2018/3/19）
 * （6）新增对动物图片导入的支持；（2018/4/17）
 * （7）修改植物和林业图片的导入支持；（2018/4/19）
 * （8）新增对南海海洋标本图片的导入支持；（2018/5/7）
 **/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;

class ImportCollectionImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection-image:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '藏品图片批量导入功能';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function isImage($filename)
    {
        $types = '.gif|.jpeg|.png|.bmp|.jpg';
        $ext = strrchr(strtolower($filename), '.');
        return stripos($types, $ext);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('开始导入藏品图片');

        $path = storage_path('import');
        $files = scandir($path);
        foreach ($files as $file) {
            if (!is_dir($file) && strpos($file, '.') != false && $this->isImage($file)) {
                $serial = substr($file, 0, strcspn($file, '_'));
                //$this->comment($serial);

                // $farm = \App\Farm::where('serial', '=', $serial)->first();
                // if ($farm != null) {
                //     $md5_str = md5_file($path . '/' . $file);

                //     $collectionImage = \App\CollectionImage::where('collectible_type', '=', \App\Farm::class)->where('collectible_id', '=', $farm)->where('hash', '=', $md5_str)->first();

                //     if ($collectionImage == null) {
                //         $entension = strrchr($file, '.');
                //         $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file), 0, 4) . $entension;

                //         $path2 = 'storage/collection/farm/' . $pic_name;
                //         rename($path . '/' . $file, public_path('storage/collection/farm/') . $pic_name);
                //         $collectionImage = new \App\CollectionImage;
                //         $collectionImage->path = $path2;
                //         $collectionImage->thumb = $path2;
                //         $collectionImage->hash = $md5_str;
                //         $collectionImage->collectible_type = \App\Farm::class;
                //         $collectionImage->collectible_id = $farm->id;
                //         if ($collectionImage->save()) {
                //             $this->comment($file . ' : 导入成功');
                //         } else {
                //             $this->comment($file . ' : 导入图片已存在');
                //         }
                //     } else {
                //         $this->comment($file . ' : 序列号不存在');
                //     }
                // }


                //$this->comment(substr($serial, 0, 1));
                //Log::info(substr($serial, 0, 1));
                if (substr($serial, 0, 1) == 'A') {
                    $prefix = 'farm';
                } else if (substr($serial, 0, 3) == 'C06') {
                    $prefix = 'soil_small';
                } else if (substr($serial, 0, 3) == 'C05') {
                    $prefix = 'soil_big';
                } else if (substr($serial, 0, 3) == 'C01') {
                    $prefix = 'soil';
                } else if (substr($serial, 0, 1) == 'C') {
                    $prefix = 'rock';
                } else if (substr($serial, 0, 1) == 'D') {
                    $prefix = 'plant';
                } else if (substr($serial, 0, 1) == 'E' || substr($serial, 0, 1) == 'H') {
                    $prefix = 'animal';
                } else if (substr($serial, 0, 1) == 'D') {
                    $prefix = 'forestry';
                } lse {
                    rename($path . '/' . $file, $path . '/.serial_not_exist/' . $file);
                    $this->comment($file . ' : Serial Not Exist.');
                    continue;
                }

                $collection = DB::table(str_plural($prefix))->where('serial', '=', $serial)->whereNull('deleted_at')->first();
                if ($collection != null) {
                    $md5_str = md5_file($path . '/' . $file);


                    $collectionImage = \App\CollectionImage::where('collectible_type', '=', 'App\\' . studly_case($prefix))->where('collectible_id', '=', $collection->id)->where('hash', '=', $md5_str)->first();

                    if ($collectionImage == null) {
                        $entension = strrchr($file, '.');
                        $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file), 0, 4) . $entension;

                        $path2 = 'storage/collection/' . $prefix . '/' . $pic_name;
                        rename($path . '/' . $file, public_path('storage/collection/' . $prefix . '/') . $pic_name);
                        $collectionImage = new \App\CollectionImage;
                        $collectionImage->path = $path2;
                        $collectionImage->thumb = $path2;
                        $collectionImage->hash = $md5_str;
                        $collectionImage->collectible_type = 'App\\' . studly_case($prefix);
                        $collectionImage->collectible_id = $collection->id;

                        $prefixs = explode('_', $file);
                        if ($prefixs != null && count($prefixs) == 4) {
                            $collectionImage->target = iconv("GB2312", "UTF-8", $prefixs[2]);
                        }

                        if ($collectionImage->save()) {

                            //记录日志
                            $collectionImage->collectible;
                            $eventModelName = 'App\\Events\\' . studly_case($prefix) . 'Event';
                            $eventClass = new \ReflectionClass($eventModelName);
                            event($eventClass->newInstance('saveImage', '172.0.0.1', $collectionImage)); //添加日记事件

                            $this->comment($file . ' : Import Success.');
                        } else {
                            rename($path . '/' . $file, $path . '/.save_error/' . $file);
                            $this->comment($file . ' : Save Image Fail.');
                        }
                    } else {
                        rename($path . '/' . $file, $path . '/.image_exist/' . $file);
                        $this->comment($file . ' : Image Exist.');
                    }
                } else {
                    rename($path . '/' . $file, $path . '/.serial_not_exist/' . $file);
                    $this->comment($file . ' : Serial Not Exist');
                }
            }
        }
    }
}
