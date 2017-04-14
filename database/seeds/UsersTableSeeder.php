<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$users = App\User::all();
        if($users == null || count($users) == 0){
            $user = new App\User;
            $user->name = 'admin';
            $user->email = 'admin@'.config('app.theme').'.com';
            $user->password = bcrypt('123456');
            $user->type = App\User::TYPE_ADMIN;
            $user->save();
        }*/

        factory('App\User', 50)->create();
    }
}
