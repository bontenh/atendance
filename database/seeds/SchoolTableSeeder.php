<?php

use Illuminate\Database\Seeder;

class SchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            'school_name'=>'本校',
        ]);
        DB::table('schools')->insert([
            'school_name'=>'本町２校',
        ]);
    }
}
