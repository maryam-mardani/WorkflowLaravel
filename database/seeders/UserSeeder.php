<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('users')->truncate();
        DB::table('organs')->truncate();
        DB::table('user_organs')->truncate();

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'مدیر سیستم',
            'user_name' => 'administrator',
            'personeli' => '-',
            'password' => Hash::make('1qaz@WSX'),
        ]);


        DB::table('organs')->insert(['id' => 1,'title' => 'مدیر سیستم']);
        DB::table('user_organs')->insert(['id' => 1,'user_id' => '1','organ_id' => '1']);

        Schema::enableForeignKeyConstraints();
    }

}
