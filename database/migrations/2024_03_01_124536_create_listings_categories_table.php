<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("listing_id");
            $table->unsignedBigInteger("category_id");
            $table->foreign("listing_id")->references("id")->on("listings");
            $table->foreign("category_id")->references("id")->on("categories");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings_categories');
    }
};
