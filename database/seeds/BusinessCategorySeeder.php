<?php

use App\Enums\BusinessCategoryEnum;
use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Entertainment',
            'Food and drink',
            'Game',
            'DIY & Tips',
            'Family & relationships',
            'Science & Education',
            'Arts and music',
            'Hot trend',
            'Fitness and wellness',
            'ASMR & slime',
            'Vehichles',
            'Pets',
            'Drama story',
            'Makeups',
            'Shopping & Fashion',
            'Anime',
            'Sport and outdoors',
            'Travel',
            'Dance',
        ];

        foreach ($categories as $category) {
            BusinessCategory::firstOrCreate([
                'name' => $category,
            ], [
                'status' => BusinessCategoryEnum::STATUS_ACTIVE
            ]);
        }
    }
}
