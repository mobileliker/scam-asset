<?php

/**
 * @version: 1.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1）注释提供的命令行范例；
 * （2）添加二维码生成的命令行工具；
 *
 * @version ：2.0.2
 * @author ：wuzhihui
 * @date : 2017/12/13
 * @description :
 * (1) 添加生成word文档的命令；(2017/12/13)
 */

namespace App\Console;

use App\Traits\CommonTraits;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\GenerateQrcode', //二维码生成的命令行工具
        Commands\ImportCollectionImageCommand::class, //导入图片
        Commands\GenerateWordDocument::class, //生成word文档
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
