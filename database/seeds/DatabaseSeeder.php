<?php

use Illuminate\Database\Seeder;

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
