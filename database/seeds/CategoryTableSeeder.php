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
        App\Category::where('serial', 'like', 'storage_location%')->delete();
        $category = App\Category::firstOrCreate(['name' => '保存地点', 'value' => 'storage_location', 'serial' => 'storage_location']); //保存地点
        /*for($i = 1; $i <=3; $i++){
            for($j = 1; $j <= 9; $j++){
                $k = '三号楼'.$i.'0'.$j;
                $category2 = App\Category::firstOrCreate(['name' => $k, 'value' => $k, 'serial' => $category->serial.'-'.$k, 'pid' => $category->id]);
            }
        }*/
        $values = ['206', '303', '305', '306', '308', '309'];
        foreach($values as $value){
            $k = '三号楼'.$value;
            $category2 = App\Category::firstOrCreate(['name' => $k, 'value' => $k, 'serial' => $category->serial.'-'.$k, 'pid' => $category->id]);
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

        $category = App\Category::firstOrCreate(['name' => '分类', 'value' => 'category', 'serial' => 'category']); //分类
        $category2 = App\Category::firstOrCreate(['name' => '文物', 'value' => '4010000', 'serial' => $category->serial . '-' . '文物', 'pid' => $category->id]);
        $category3 = App\Category::firstOrCreate(['name' => '不可移动文物', 'value' => '4010100', 'serial' => $category2->serial . '-' . '不可移动文物', 'pid' => $category2->id]);
        $map = [
            '古遗址' => '4010101',
            '古墓葬' => '4010102',
            '古建筑' => '4010103',
            '石窟寺' => '4010104',
            '原址石刻' => '4010105',
            '近现代重要史迹及代表性建筑' => '4010106',
            '其他不可移动文物' => '4010199',
        ];
        foreach($map as $name=>$value) {
            $category4 = App\Category::firstOrCreate(['name' => $name, 'value' => $value, 'serial' => $category3->serial . '-' . $name, 'pid' => $category3->id]);
        }


        $category3 = App\Category::firstOrCreate(['name' => '可移动文物', 'value' => '4010200', 'serial' => $category2->serial . '-' . '可移动文物', 'pid' => $category2->id]);
        $map = [
            '考古发掘出土文物' => '4010201',
            '传世文物' => '4010202',
            '古生物化石' => '4010203',
            '其他可移动文物' => '4010299',
        ];
        foreach($map as $name=>$value) {
            $category4 = App\Category::firstOrCreate(['name' => $name, 'value' => $value, 'serial' => $category3->serial . '-' . $name, 'pid' => $category3->id]);
        }


        $category2 = App\Category::firstOrCreate(['name' => '陈列品', 'value' => '4020000', 'serial' => $category->serial . '-' . '陈列品', 'pid' => $category->id]);
        $category3 = App\Category::firstOrCreate(['name' => '标本', 'value' => '4020100', 'serial' => $category2->serial . '-' . '标本', 'pid' => $category2->id]);
        $map = [
            '动物标本' => '4020101',
            '人体标本' => '4020102',
            '人体病理标本' => '4020103',
            '植物标本' => '4020104',
            '医药标本' => '4020105',
            '矿物标本' => '4020106',
            '其他标本' => '4020199',
        ];
        foreach($map as $name=>$value) {
            $category4 = App\Category::firstOrCreate(['name' => $name, 'value' => $value, 'serial' => $category3->serial . '-' . $name, 'pid' => $category3->id]);
        }

        $category3 = App\Category::firstOrCreate(['name' => '模型', 'value' => '4020200', 'serial' => $category2->serial . '-' . '模型', 'pid' => $category2->id]);
        $map = [
            '天体模型' => '4020201',
            '生物模型' => '4020202',
            '人体模型' => '4020203',
            '人体病理模型' => '4020204',
            '其他模型' => '4020299',
        ];
        foreach($map as $name=>$value) {
            $category4 = App\Category::firstOrCreate(['name' => $name, 'value' => $value, 'serial' => $category3->serial . '-' . $name, 'pid' => $category3->id]);
        }

        $category3 = App\Category::firstOrCreate(['name' => '其他陈列品', 'value' => '4029900', 'serial' => $category2->serial . '-' . '其他陈列品', 'pid' => $category2->id]);





    }
}
