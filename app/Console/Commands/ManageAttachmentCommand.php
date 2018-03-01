<?php

/**
 * 附件管理命令行工具
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * (1)基本功能；（2018/3/1）
 */

namespace App\Console\Commands;

use App\Attachment;
use Illuminate\Console\Command;
use App\User;

class ManageAttachmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachment:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '管理附件';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('开始导入附件');

        $user = User::where('name', '=', 'batch-user')->first();
        $path = storage_path('app/public/upload/attachment');
        $files = scandir($path);
        foreach ($files as $file) {
            if (!is_dir($file) && strpos($file, '.') != false) {
                $absolutePath = 'storage/upload/attachment/' . $file;
                //$this->comment($absolutePath);
                $attachment = Attachment::where('path', '=', $absolutePath)->first();
                if ($attachment == null) {
                    $attachment = new Attachment;
                    $attachment->name = $file;
                    $attachment->path = $absolutePath;
                    $attachment->user_id = $user->id;
                    $attachment->save();
                    $this->comment($file . ': 导入成功' );
                } else {
                    $this->comment($file . ': 文件已存在');
                }
            }
        }

        $this->comment('导入附件结束');
    }
}
