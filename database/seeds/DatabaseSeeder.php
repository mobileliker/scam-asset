<?php

use Illuminate\Database\Seeder;

/**
 * 基础填充器
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/11/1
 * @description : 
 * (1)修复生产环境无法正确执行InitSeeder的错误；（2017/11/1）
 **/

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!config('app.debug')){
            $this->call(UsersTableSeeder::class);
            //$this->call(InfoTableSeeder::class);
            $this->call(CategoryTableSeeder::class);
        }else{
            $this->call(InitSeeder::class);
        }
         
    }
}
