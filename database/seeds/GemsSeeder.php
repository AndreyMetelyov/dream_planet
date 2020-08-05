<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gems')->insert([
            ['gemtype' => 1, 'earner' => 1],
            ['gemtype' => 2, 'earner' => 1],
            ['gemtype' => 3, 'earner' => 1],
        ]);
    }
}
