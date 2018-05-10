<?php

/**
 * @version: 1.0 批量生成固定资产二维码的命令行工具
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 *
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Asset;
use IQrcode;

class GenerateQrcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrcode:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批量生成所有固定资产的二维码';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  批量生成固定自唱二维码的命令行工具
     *
     * @return null
     */
    public function handle()
    {
        //
        $assets = Asset::all();
        foreach($assets as $asset){
            $qrcode_path = 'storages/qrcode/'.$asset->serial.'.png';
            if(!file_exists($qrcode_path)){
                if($asset->serial != null && $asset->serial != ""){
                    $link = url('s?c='.$asset->serial);
                    IQrcode::generate2($link, $asset->serial);
                }
            }
        }

    }
}
