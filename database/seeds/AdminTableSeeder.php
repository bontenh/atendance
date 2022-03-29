<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name'=>'admin',
            'password'=>password_hash('1q1q',PASSWORD_DEFAULT),
            'created_at'=>now(),
        ]);
        DB::table('admins')->insert([
            'name'=>'augustus',
            'password'=>password_hash('roma',PASSWORD_DEFAULT),
            'created_at'=>now(),
        ]);
    }
}
