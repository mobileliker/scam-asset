<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Command description';

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
        $types = '.gif|.jpeg|.png|.bmp';
        $ext = strrchr($filename, '.');
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

                $farm = \App\Farm::where('serial', '=', $serial)->first();
                if ($farm != null) {
                    $md5_str = md5_file($path . '/' . $file);

                    $collectionImage = \App\CollectionImage::where('collectible_type', '=', \App\Farm::class)->where('collectible_id', '=', $farm)->where('hash', '=', $md5_str)->first();

                    if ($collectionImage == null) {
                        $entension = strrchr($file, '.');
                        $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file), 0, 4) . $entension;

                        $path2 = 'Storage/collection/farm/' . $pic_name;
                        rename($path . '/' . $file, public_path('storage/collection/farm/') . $pic_name);
                        $collectionImage = new \App\CollectionImage;
                        $collectionImage->path = $path2;
                        $collectionImage->thumb = $path2;
                        $collectionImage->hash = $md5_str;
                        $collectionImage->collectible_type = \App\Farm::class;
                        $collectionImage->collectible_id = $farm->id;
                        if ($collectionImage->save()) {
                            $this->comment($file . ' : 导入成功');
                        } else {
                            $this->comment($file . ' : 导入图片已存在');
                        }
                    } else {
                        $this->comment($file . ' : 序列号不存在');
                    }
                }
            }
        }
    }
}
