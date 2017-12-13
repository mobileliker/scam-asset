<?php

/**
 * 生成word文档命令
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/13
 * @description :
 * （1）基本功能；（2017/12/13）
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Rock;

class GenerateWordDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:generate';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('开始生成word文档');

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

        foreach($objs as $key => $obj) {

            $spanTableStyleName = ''. ($key + 1);
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
            if($image != null) {
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

        $this->comment('完成文档生成');
    }
}
