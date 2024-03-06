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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("listing_id");
            $table->string("deal_name")->nullable();
            $table->string("currency")->nullable();
            $table->string("discount_price")->nullable();
            $table->string("actual_price")->nullable();
            $table->enum("billing_interval", ['lifetime', 'annual', 'monthly', 'once'])->nullable();
            $table->enum("type", ['url', 'code'])->nullable();
            $table->string("coupon_code")->nullable();
            $table->string("link")->nullable();
            $table->foreign("listing_id")->references("id")->on("listings");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
