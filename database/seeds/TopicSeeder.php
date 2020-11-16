<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
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
        ] as $title) {
            Topic::firstOrCreate([
                'title' => $title,
            ], [
                'image' => 'https://images.squarespace-cdn.com/content/v1/53883795e4b016c956b8d243/1551438228969-H0FPV1FO3W5B0QL328AS/ke17ZwdGBToddI8pDm48kIhenacT88MnrKvY1JbhsmgUqsxRUqqbr1mOJYKfIPR7LoDQ9mXPOjoJoqy81S2I8N_N4V1vUb5AoIIIbLZhVYxCRW4BPu10St3TBAUQYVKcq1HrAUeB6VAkYYiXCSFJvA-_I3iLvlr6tW6s9hAWr_JP9HyLwihmRkgjjYg_eCzi/chup-anh-thuc-an-1.jpg'
            ]);
        }
    }
}
