<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            ["type" => "user", "name" => "muneeb","email_verified_at" => Carbon::now(),  "email" => "muneeb@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1"],
            ["type" => "user", "name" => "sohail","email_verified_at" => Carbon::now(), "email" => "sohail@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1"],
            ["type" => "user", "name" => "ali","email_verified_at" => Carbon::now(), "email" => "ali@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1"],
       ];

       
       // assigning role to each of the user
       foreach($users as $userData){
        $user = User::create($userData);
        $user->assignRole('user');
       }
    }
}
