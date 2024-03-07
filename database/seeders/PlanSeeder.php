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
            [
                "plan_type" => "Monthly",
                "plan_name" => "First Month", 
                "plan_id" => "price_1OrHLaLyI7mncMRJA9dBBgIa", 
                "discounted_price" => "1",
                "recurring_price" => "5.99",  
                "description"=>"Unlimited categories, Business tagline & full description, Link to website", 
                "currency"=>"USD"
            ],[
                "plan_type" => "Yearly",
                "plan_name" => "Yearly", 
                "plan_id" => "price_1OjJt0LyI7mncMRJX2AStM7F", 
                "discounted_price" => null,
                "recurring_price" => "59", 
                "description"=>"Unlimited categories, Business tagline & full description, Link to website", 
                "currency"=>"USD"
            ],[
                "plan_type" => "Featured",
                "plan_name" => "Yearly",
                "plan_id" => "price_1OjJtVLyI7mncMRJUzV3ysrC", 
                "discounted_price" => null,
                "recurring_price" => "199", 
                "description"=>"Your listing will show on top of the home page, Your business will show on top on your chosen category, Unlimited categories, Business tag line & full description, Link to website, Be able to add deals", 
                "currency"=>"USD"
            ],
        ];

        foreach($plans as $plan){
            Plan::create($plan);
        }
    }
}
