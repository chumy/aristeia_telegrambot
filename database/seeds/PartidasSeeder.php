<?php

use App\Partida;
use Illuminate\Database\Seeder;


class PartidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $time = strtotime('08/24/2019 18:20');
        $newformat = date('Y-m-d H:i',$time);
        factory(Partida::class)->create([
            'fecha' => $newformat,
            'user_id' => 1,
        ]);


echo $newformat;
        factory(Partida::class,10)->create();
    }
}
