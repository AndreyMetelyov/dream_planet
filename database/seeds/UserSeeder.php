<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'test', 'email' => 'test@test', 'password' => password_hash('test1234', PASSWORD_BCRYPT), 'group' => 'gnome'],
            ['name' => 'admin', 'email' => 'admin@admin', 'password' => password_hash('admin1234', PASSWORD_BCRYPT), 'group' => 'elf'],
            ['name' => 'elf1', 'email' => 'elf1@elf1', 'password' => password_hash('elf11234', PASSWORD_BCRYPT), 'group' => 'elf'],
            ['name' => 'gnome', 'email' => 'gnome@gnome', 'password' => password_hash('gnome1234', PASSWORD_BCRYPT), 'group' => 'gnome'],
        ]);
        DB::table('users')->insert([
            [
                'name' => 'mgnome', 'email' => 'mgnome@mgnome', 'password' => password_hash('mgnome1234', PASSWORD_BCRYPT),
                'group' => 'gnome', 'is_master_gnome' => true
            ]
        ]);
    }
}
