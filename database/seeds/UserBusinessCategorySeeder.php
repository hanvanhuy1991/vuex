<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserBusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::role(UserRole::BUSINESS_ACCOUNT)->limit(20)->get();
        foreach ($users as $user) {
            $num = mt_rand(1, 3);
            for ($i = 0; $i < $num; $i++) {
                $user->userBusinessCategories()->firstOrCreate([
                    'business_category_id' => mt_rand(1, 15)
                ]);
            }
        }
    }
}
