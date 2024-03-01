<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ["slug" => "3d_tours", "category_name" => "3D Tours", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "accounting", "category_name" => "Accounting", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "advertising", "category_name" => "Advertising", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "booking_channel", "category_name" => "Booking Channel", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "central_reservation_system", "category_name" => "Central Reservation System", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "certifications", "category_name" => "Certifications", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "channel_management", "category_name" => "Channel Management", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "chatbot", "category_name" => "ChatBot", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "check_in_software", "category_name" => "Check-in Software", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "cleaning_management", "category_name" => "Cleaning Management", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "communication", "category_name" => "Communication", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "consultancy", "category_name" => "Consultancy", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "copywritting", "category_name" => "Copywritting", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "crm", "category_name" => "CRM", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "data_solutions", "category_name" => "Data Solutions", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "dynamic_pricing", "category_name" => "Dynamic Pricing", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "education_and_training", "category_name" => "Education & Training", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "events", "category_name" => "Events", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "floor_plans", "category_name" => "Floor Plans", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "guest_communication", "category_name" => "Guest Communication", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "guest_verification", "category_name" => "Guest Verification", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "home_authentication", "category_name" => "Home Authentication", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "house_keeping_and_maintenance", "category_name" => "House Keeping and Maintenance", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "innovative_services", "category_name" => "Innovative Services", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "insurance", "category_name" => "Insurance", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "key_safes_and_drop_boxes", "category_name" => "Key Safes & Drop Boxes", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "lead_management", "category_name" => "Lead Management", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "listing_sites", "category_name" => "Listing Sites", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "marketing_automation", "category_name" => "Marketing Automation", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "noise_control", "category_name" => "Noise Control", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "other", "category_name" => "other", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "payment_processing", "category_name" => "Payment Processing", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "payment_solutions", "category_name" => "Payment Solutions", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "photography", "category_name" => "Photography", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "property_management_system", "category_name" => "Property Management System", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "referral_networks", "category_name" => "Referral Networks", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "revenue_management", "category_name" => "Revenue Management", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "smart_locks_and_keyless_entry", "category_name" => "Smart Locks & Keyless Entry", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "supplies_and_interior_design", "category_name" => "Supplies & Interior Design", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "travel_marketplace", "category_name" => "Travel Marketplace", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "voice_solutions", "category_name" => "Voice Solutions", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "website_building", "category_name" => "Website Building", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "welcome_apps", "category_name" => "Welcome Solutions", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],
            ["slug" => "wifi_solutions", "category_name" => "Wifi Solutions", "category_image" => "assets/category_images/default.svg", "status"=>"activate"],

        ];

        foreach($categories as $category){
            Category::create($category);
        }
    }
}
