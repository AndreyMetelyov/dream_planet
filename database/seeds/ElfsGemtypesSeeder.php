<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElfsGemtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('elfs_gemtypes')->insert([
            ['userId' => 2, 'gemtypeId' => 1, 'coeff' => 0.3],
            ['userId' => 2, 'gemtypeId' => 2, 'coeff' => 0.3],
            ['userId' => 2, 'gemtypeId' => 3, 'coeff' => 0.4],

            ['userId' => 3, 'gemtypeId' => 1, 'coeff' => 0.6],
            ['userId' => 3, 'gemtypeId' => 2, 'coeff' => 0.3],
            ['userId' => 3, 'gemtypeId' => 3, 'coeff' => 0.1]

        ]);
    }
}
