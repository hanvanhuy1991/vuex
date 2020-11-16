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
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(TopicSeeder::class);
        $this->call(ShareSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(LabelReasonSeeder::class);
        $this->call(PostSeeder::class);
//        $this->call(BusinessCategorySeeder::class);
//        $this->call(UserBusinessCategorySeeder::class);
    }
}
