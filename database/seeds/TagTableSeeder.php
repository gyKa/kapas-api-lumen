<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tag')->insert([
            ['title' => 'php'],
            ['title' => 'clean_code'],
            ['title' => 'code_coverage'],
            ['title' => 'phpunit'],
            ['title' => 'laravel'],
            ['title' => 'homestead'],
            ['title' => 'programming'],
            ['title' => 'nutrition'],
            ['title' => 'cycling']
        ]);
    }
}
