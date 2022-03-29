<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NoteTableSeeder::class);
        $this->call(SchoolTableSeeder::class);
        $this->call(UsertableTableSeeder::class);
        $this->call(AdminTableSeeder::class);
    }
}
