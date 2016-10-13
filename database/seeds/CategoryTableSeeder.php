<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory('App\Category', 50)->create();

        //storage_location
        $category = App\Category::firstOrCreate(['name' => '保存地点', 'value' => 'storage_location', 'serial' => 'storage_location']); //保存地点
        for($i = 1; $i <=3; $i++){
            for($j = 1; $j <= 9; $j++){
                $k = '三号楼'.$i.'0'.$j;
                $category2 = App\Category::firstOrCreate(['name' => $k, 'value' => $k, 'serial' => $category->serial.'-'.$k, 'pid' => $category->id]);
            }
        }

        //使用方向
        $category = App\Category::firstOrCreate(['name' => '使用方向', 'value' => 'application', 'serial' => 'application']); //使用方向
        $values = ['陈列','教学','科研','行政','生活','生产','技术开发','社会服务','借出','出租','对外投资','资产担保','经营','其它',];
        foreach($values as $value) {
            $category2 = App\Category::firstOrCreate(['name' => $value, 'value' => $value, 'serial' => $category->serial . '-' . $value, 'pid' => $category->id]);
        }

        //经费科目
        $category = App\Category::firstOrCreate(['name' => '经费科目', 'value' => 'course', 'serial' => 'course']); //经费科目
        $values = ['教学','科研','基建','自筹','贷款','捐赠','财政专款','研究生','配套','行政','211经费','十五投资','985经费','高水平建设','重点实验室','重点专业','中央共建'];
        foreach($values as $value) {
            $category2 = App\Category::firstOrCreate(['name' => $value, 'value' => $value, 'serial' => $category->serial . '-' . $value, 'pid' => $category->id]);
        }


        //card
        $category = App\Category::firstOrCreate(['name' => '经费卡号', 'value' => 'card', 'serial' => 'card']); //保存地点
        $values = ['9700-32010097'];
        foreach($values as $value) {
            $category2 = App\Category::firstOrCreate(['name' => $value, 'value' => $value, 'serial' => $category->serial . '-' . $value, 'pid' => $category->id]);
        }

        //gallery
        $category = App\Category::firstOrCreate(['name' => '展厅', 'value' => 'gallery', 'serial' => 'gallery']); //保存地点
        $values = [
            '农业文明史展厅A（单据号为1xxxxxxx）',
            '传统农具展厅B（单据号为2xxxxxxx）',
            '土壤与岩石展厅C（单据号为3xxxxxxx）',
            '植物世界展厅D（单据号为4xxxxxxx）',
            '动物世界展厅E（单据号为5xxxxxxx）',
            '昆虫世界展厅F（以盒为单位编流水号）（单据号为6xxxxxxx）',
            '林业资源与生产展厅G（单据号为7xxxxxxx）',
            '南海海洋生物展厅H（单据号为8xxxxxxx）',
            '可转让科技成果专题展厅I（单据号为9xxxxxxx）',
        ];
        foreach($values as $value) {
            $category2 = App\Category::firstOrCreate(['name' => $value, 'value' => $value, 'serial' => $category->serial . '-' . $value, 'pid' => $category->id]);
        }
    }
}
