<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
                'email' => 'admin@comuni.com',
        ], [
            "name" =>  "Admin",
            "phone" => "09435353453",
            "birthday" => "1995-10-10",
            "avatar" => "https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg",
            "gender" =>  1,
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole('admin');
    }
}
