<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // remove this seeder when Admin creates and edit the plans data
        $plans = [
            ["plan_name" => "Basic", "plan_id" => "price_1OjJrqLyI7mncMRJxo1KhAy6", "price" => "1", "description"=>"This is the basic Plan", "currency"=>"USD"],
            ["plan_name" => "Pro", "plan_id" => "price_1OjJt0LyI7mncMRJX2AStM7F", "price" => "59", "description"=>"This is the Pro Plan", "currency"=>"USD"],
            ["plan_name" => "Featured", "plan_id" => "price_1OjJtVLyI7mncMRJUzV3ysrC", "price" => "59", "description"=>"This is the Featured Plan", "currency"=>"USD"],
        ];

        foreach($plans as $plan){
            Plan::create($plan);
        }
    }
}
