<?php

use Illuminate\Database\Seeder;
use App\Models\Page;


class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::firstOrCreate(['type' => 1], ['content' => '<h1>This is Term-Condition page </h1>']);
        Page::firstOrCreate(['type' => 2], ['content' => '<h1>This is About Page</h1>']);
    }
}
