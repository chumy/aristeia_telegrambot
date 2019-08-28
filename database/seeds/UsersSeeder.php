<?php

use App\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /*factory(User::class)->create([
            'chat' =>247049890,
            'name' => 'Chumy',
            'nick' => 'xChumy',
            'id' => 1,

        ]);*/
        
       factory(User::class,5)->create();

    }
}
