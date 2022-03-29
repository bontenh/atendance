<?php

use Illuminate\Database\Seeder;

class NoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->insert([
            'note'=>'通所',
        ]);
        DB::table('notes')->insert([
            'note'=>'Skype',
        ]);
        DB::table('notes')->insert([
            'note'=>'メール',
        ]);
        DB::table('notes')->insert([
            'note'=>'訪問',
        ]);
    }
}
