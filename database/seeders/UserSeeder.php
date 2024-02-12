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
       $user = [
            ["type" => "user", "name" => "muneeb", "email" => "muneeb@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1", "created_at" => Carbon::now(), "updated_at" => Carbon::now()],
            ["type" => "user", "name" => "sohail", "email" => "sohail@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1", "created_at" => Carbon::now(), "updated_at" => Carbon::now()],
            ["type" => "user", "name" => "ali", "email" => "ali@gmail.com","password" => Hash::make('muneeb123'),"tc_status" => "1", "created_at" => Carbon::now(), "updated_at" => Carbon::now()],
       ];

       User::insert($user);
    }
}
