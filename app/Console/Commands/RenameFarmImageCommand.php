<?php

/**
 * 本地使用的农具图片重命名工具
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/1/12
 * @required： 需要使用时请打开Kernel.php的注释
 * @description :
 * （1）基本功能；（2018/1/12）
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Farm;

class RenameFarmImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farm-image:rename';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '农具图片重命名';

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
        $types = '.gif|.jpeg|.png|.bmp|.jpg|.psd';
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
        $this->comment('开始重命名农具图片');

        $path = storage_path('import');
        $files = scandir($path);
        foreach ($files as $file) {
            if (!is_dir($file) && strpos($file, '.') != false && $this->isImage($file)) {
                $serial = substr($file, 0, strcspn($file, '_'));

                if(substr($serial, 0, 1) == 'A') {
                    $farms = Farm::where('odd_serial', '=', $serial)->get();
                    if(count($farms) == 1) {
                        $farm = $farms[0];
                        $entension = strrchr($file, '_');
                        $pic_name = $farm->serial . '_' . iconv("UTF-8", "GB2312", $farm->name) . $entension;
                        rename($path . '/' . $file, $path. '/rename/' . $pic_name);
                        $this->comment($file . ':重命名成功');
                    }else {
                        $this->comment($file . ':需要手工处理');
                        rename($path . '/' . $file, $path . '/.mannal/' . $file);
                    }
                } else {
                    $this->comment($file . ':不是农具图片');
                }
            }
        }
        $this->comment('重命名农具图片结束');
    }
}
