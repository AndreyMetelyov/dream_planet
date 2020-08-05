<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoefficientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coefficients')->insert([
            ['coeff_1' => 0.5, 'coeff_2' => 0.2, 'coeff_3' => 0.3]
        ]);
    }
}
