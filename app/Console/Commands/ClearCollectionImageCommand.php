<?php

/**
 * 藏品图片清理工具
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/1/23
 * @description :
 * （1）基本功能；（2018/1/23）
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CollectionImage;

class ClearCollectionImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection-image:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清理无效的藏品图片';

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
        $this->comment('开始清理无效的藏品图片');

        $path = storage_path('app/public/collection');
        $path2 = storage_path('app/trash/');

        $dirs = scandir($path);
        foreach ($dirs as $dir) {
            //$this->comment($file);
            if (!is_dir($dir)) {
                $files = scandir($path . '/' . $dir);
                foreach ($files as $file) {
                    if (!is_dir($file) && strpos($file, '.') != false && $this->isImage($file)) {
                        $image = CollectionImage::where('path', 'like', '%' . $file . '%')->first();
                        if ($image == null) {
                            rename($path . '/' . $dir . '/' . $file, $path2 . '/' . $file . '.' . date('ymdhis'));
                            $this->comment($file . ':已被删除图片');
                        } else {
                            $collection = $image->collectible;
                            if ($collection == null) {
                                rename($path . '/' . $dir . '/' . $file, $path2 . '/' . $file . '.' . date('ymdhis'));
                                $this->comment($file . ':藏品已被删除图片');
                            } else {
                                $this->comment($file . ':有效图片');
                            }
                        }
                    }
                }
            }
        }
        $this->comment('清理无效的藏品图片结束');
    }
}
