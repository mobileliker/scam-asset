<?php

/**
 * 生成word文档命令
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/13
 * @description :
 * （1）基本功能；（2017/12/13）
 *
 * @version: 2.0.3
 * @author: wuzhihui
 * @date: 2017/12/18
 * @description:
 * （1）添加土壤word文档导出；（2017/12/18）
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Rock;
use App\Soil, App\SoilBig, App\SoilSmall;

class GenerateWordDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:generate {collection : Rock}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Word文档生成命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function generateRockWordDocument()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $phpWord->setDefaultFontName('华文仿宋');
        $phpWord->setDefaultFontSize('12');

        $section = $phpWord->addSection();

        $fancyTableStyle = array(
            'borderSize' => 12,
            'borderColor' => '000000',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'valign' => 'center'
        );

        //$objs = Rock::where('category', '=', '岩石')->take(10)->get();
        $objs = Rock::get();

        $attributeCellStyle = array(
            'valign' => 'center',
        );
        $attributeFontStyle = array(
            'bold' => true
        );
        $attributeTextStyle = array(
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'bold' => true
        );

        foreach ($objs as $key => $obj) {

            $spanTableStyleName = '' . ($key + 1);
            $phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
            $table = $section->addTable($spanTableStyleName);


            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('类别', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->category);
            $table->addCell('1000', $attributeCellStyle)->addText('名称', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->name);
            $table->addCell('1000', $attributeCellStyle)->addText('编号', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->serial);

            $table->addRow('900');
            $table->addCell(null, ['gridSpan' => 6, 'valign' => 'center'])->addText('基本信息', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('英文名称', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->ename);
            $table->addCell('1000', $attributeCellStyle)->addText('产地', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->origin);
            $table->addCell('1000', $attributeCellStyle)->addText('分类', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->classification);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('特征', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->feature);
            $table->addCell('1000', $attributeCellStyle)->addText('尺寸', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->size);
            $table->addCell('1000', $attributeCellStyle)->addText('存放地点', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->storage);

            $table->addRow('5000');
            $table->addCell('1000', $attributeCellStyle)->addText('描述', $attributeFontStyle, $attributeTextStyle);
            $table->addCell(null, ['gridSpan' => 5])->addText($obj->description);

            $table->addRow('4000');
            $table->addCell('1000', $attributeCellStyle)->addText('图片', $attributeFontStyle, $attributeTextStyle);

            $image = $obj->images()->first();
            if ($image != null) {
                // list($width, $height, $type, $attr) = getimagesize(public_path($image->path));
                // if($width < $height){
                //   $back=imagecreatefrompng(public_path($image->path));
                //   $new = imagerotate($back, 90, 0);
                //   imagepng($new, public_path($image->path));
                //   unset($back);
                //   unset($new);
                // }
                //$this->comment(public_path($image->path));
                $table->addCell(null, ['gridSpan' => 5, 'valign' => 'center'])->addImage(public_path($image->path), array(
                    'width' => 450,
                    'wrappingStyle' => 'behind'
                ));
            } else {
                $table->addCell(null, ['gridSpan' => 5, 'valign' => 'center'])->addText('暂无图片');
            }

            $section->addPageBreak(); //分页符号
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('藏品信息文档.docx');

    }

    private function generateSoilWordDocument()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $phpWord->setDefaultFontName('华文仿宋');
        $phpWord->setDefaultFontSize('12');

        $section = $phpWord->addSection();

        $fancyTableStyle = array(
            'borderSize' => 12,
            'borderColor' => '000000',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'valign' => 'center'
        );

        $fancyTableStyle2 = array(
            'borderSize' => 12,
            'borderColor' => '000000',
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'valign' => 'center'
        );

        //$objs = Rock::where('category', '=', '岩石')->take(10)->get();
        $objs = Soil::get();

        $attributeCellStyle = array(
            'valign' => 'center',
        );
        $attributeFontStyle = array(
            'bold' => true
        );
        $attributeTextStyle = array(
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'bold' => true
        );
        $contentTextStyle = array(
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        );

        foreach ($objs as $key => $obj) {
            $soilBigs = $obj->soilBigs;
            $soilSmalls = $obj->soilSmalls;

            $spanTableStyleName = '' . ($key + 1);
            $phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
            $table = $section->addTable($spanTableStyleName);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('类别', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText('土壤标本', null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('名称', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->name, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('编号', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->serial, null, $contentTextStyle);

            $table->addRow('450');
            $table->addCell(null, ['gridSpan' => 6, 'valign' => 'center'])->addText('基本信息', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('英文名称', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->ename, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('地区', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->region, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('段面数量', $attributeFontStyle, $attributeTextStyle);
            if ($soilBigs != null) {
                $table->addCell('2000', $attributeCellStyle)->addText(count($soilBigs), null, $contentTextStyle);
            } else {
                $table->addCell('2000', $attributeCellStyle)->addText('0', null, $contentTextStyle);
            }

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('纸盒数量', $attributeFontStyle, $attributeTextStyle);
            if ($soilBigs != null) {
                $table->addCell('2000', $attributeCellStyle)->addText(count($soilSmalls), null, $contentTextStyle);
            } else {
                $table->addCell('2000', $attributeCellStyle)->addText('0', null, $contentTextStyle);
            }
            $table->addCell('1000', $attributeCellStyle)->addText('编辑时间', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->updated_at, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('存放地点', $attributeFontStyle, $attributeTextStyle);
            $storage = '';
            foreach ($soilSmalls as $soilSmall) {
                $storage = $storage . $soilSmall->storage . ',';
            }
            foreach ($soilBigs as $soilBig) {
                $storage = $storage . $soilBig->storage . ',';
            }
            $table->addCell('2000', $attributeCellStyle)->addText($storage, null, $contentTextStyle);

            $table->addRow('450');
            $table->addCell(null, ['gridSpan' => 6, 'valign' => 'center'])->addText('采集信息', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('采集地点', $attributeFontStyle, $attributeTextStyle);
            $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addText($obj->origin, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('经纬度', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->location, null, $contentTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('海拔', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->altitude, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('地形', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->terrain, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('坡度', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->gradient, null, $contentTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('母质', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->matrix, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('植被', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->vegetation, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('利用状况', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->use_status, null, $contentTextStyle);

            $table->addRow('900');
            $table->addCell('1000', $attributeCellStyle)->addText('土层深度', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->depth, null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('冻层深度', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText('', null, $contentTextStyle);
            $table->addCell('1000', $attributeCellStyle)->addText('采集者', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('2000', $attributeCellStyle)->addText($obj->collecter, null, $contentTextStyle);

            $table->addRow('5000');
            $table->addCell('1000', $attributeCellStyle)->addText('描述', $attributeFontStyle, $attributeTextStyle);
            $table->addCell(null, ['gridSpan' => 5])->addText('');

            $section->addPageBreak(); //分页符号

            $spanTableStyleName = 'A' . ($key + 1);
            $phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle2);
            $table = $section->addTable($spanTableStyleName);

            $table->addRow('450');
            $table->addCell(null, ['gridSpan' => 6, 'valign' => 'center'])->addText('图片信息', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('450');
            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('剖面照片', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('段面照片', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('5700');
//            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('剖面照片', $attributeFontStyle, $attributeTextStyle);
            $image1 = $obj->images()->where('target', 'like', '剖面')->first();
            if ($image1 != null) {
                $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addImage(public_path($image1->path), array(
                    'width' => 250,
                    'wrappingStyle' => 'behind'
                ));
            } else {
                $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addText('暂无图片', null, $contentTextStyle);
            }

            if ($soilBigs != null && count($soilBigs) > 0) {
                $image = $soilBigs[0]->images()->first();
                if ($image != null) {
                    $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addImage(public_path($image->path), array(
                        'width' => 250,
                        'wrappingStyle' => 'behind'
                    ));
                } else {
                    $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addText('暂无图片', null, $contentTextStyle);
                }
            } else {
                $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('无段面标本', null, $contentTextStyle);
            }

            $table->addRow('450');
            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('纸盒标本1', $attributeFontStyle, $attributeTextStyle);
            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('纸盒标本2', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('5700');

            if ($soilSmalls != null) {
                foreach ($soilSmalls as $key => $soilSmall) {
                    $image = $soilSmall->images()->first();
                    if ($image != null) {
                        $table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])->addImage(public_path($image->path), array(
                            'width' => 250,
                            'wrappingStyle' => 'behind'
                        ));
                    } else {
                        $table->addCell(null, ['gridSpan' => 5, 'valign' => 'center'])->addText('暂无图片');
                    }
                }
            }
            if ($soilSmalls == null) {
                $smallCount = 2;
            } else $smallCount = 2 - count($soilSmalls);
            while ($smallCount > 0) {
                $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('无纸盒标本', $attributeFontStyle, $attributeTextStyle);
                $smallCount = $smallCount - 1;
            }

//            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('纸盒标本1', $attributeFontStyle, $attributeTextStyle);
//            $table->addCell('4500', ['gridSpan' => 3, 'valign' => 'center'])->addText('纸盒标本1', $attributeFontStyle, $attributeTextStyle);

            $table->addRow('5000');
            $table->addCell('1000', $attributeCellStyle)->addText('生境照片', $attributeFontStyle, $attributeTextStyle);
//            $table->addCell(null, ['gridSpan' => 5])->addText('');

            $image = $obj->images()->where('target', 'like', '植被')->first();
            if ($image != null) {
                // list($width, $height, $type, $attr) = getimagesize(public_path($image->path));
                // if($width < $height){
                //   $back=imagecreatefrompng(public_path($image->path));
                //   $new = imagerotate($back, 90, 0);
                //   imagepng($new, public_path($image->path));
                //   unset($back);
                //   unset($new);
                // }
                //$this->comment(public_path($image->path));
                $table->addCell(null, ['gridSpan' => 5, 'valign' => 'center'])->addImage(public_path($image->path), array(
                    'width' => 450,
                    'wrappingStyle' => 'behind'
                ));
            } else {
                $table->addCell(null, ['gridSpan' => 5, 'valign' => 'center'])->addText('暂无图片');
            }

            $section->addPageBreak(); //分页符号
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('藏品信息文档（土壤）.docx');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('开始生成word文档');
        $collection = $this->argument('collection');
        if ($collection == 'Rock') {
            $this->generateRockWordDocument();
        } else if ($collection == 'Soil') {
            $this->generateSoilWordDocument();
        } else {
            $this->comment('输入的参数有误');
        }
        $this->comment('完成文档生成');
    }
}
