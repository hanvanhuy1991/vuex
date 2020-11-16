<?php

use App\Enums\ReportEnum;
use App\Models\LabelReason;
use Illuminate\Database\Seeder;

class LabelReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userReasons = [
            [
                'label' => 'Pretending to be someone',
                'items' => [
                    'Me',
                    'A Friend',
                    'Celebrity'
                ]
            ],
            [
                'label' => 'Posting inappropriate things',
                'items' => [
                    'Intellectual property violation',
                    'Nudity / sexual activity',
                    'Bullying / harassment',
                    'Hate speech / symbols',
                    'Fraud / scam',
                    'Bullying / harassment',
                    'Violence / dangerous organisations',
                    'Unauthorised sales / regulated goods',
                    'False information',
                ]
            ]
        ];

        $postReasons = [
            [
                'label' => 'Posting inappropriate things',
                'items' => [
                    'Intellectual property violation',
                    'Nudity / sexual activity',
                    'Bullying / harassment',
                    'Hate speech / symbols',
                    'Fraud / scam',
                    'Bullying / harassment',
                    'Violence / dangerous organisations',
                    'Unauthorised sales / regulated goods',
                    'False news',
                ]
            ]
        ];

        $this->store($userReasons, ReportEnum::REPORT_USER);
        $this->store($postReasons, ReportEnum::REPORT_POST);
    }

    private function store($labels, $type)
    {
        foreach ($labels as $label) {
            $reasons = [];
            foreach ($label['items'] as $item) {
                $reasons[] = [
                    'content' => $item
                ];
            }
            $labelReason = LabelReason::firstOrCreate([
                'content' => $label['label'],
                'type' => $type
            ]);

            if ($labelReason->wasRecentlyCreated) {
                $labelReason->reasons()->createMany($reasons);
            }
        }
    }
}
