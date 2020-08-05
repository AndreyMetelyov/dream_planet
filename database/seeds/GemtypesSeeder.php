<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GemtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gem_types')->insert([
            ['type' => 'ruby'],
            ['type' => 'sapphire'],
            ['type' => 'emerald']
        ]);
    }
}
