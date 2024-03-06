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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('company_name')->nullable();
            $table->string('company_link')->nullable();
            $table->string('company_tagline')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('company_logo')->nullable();
            $table->enum('status', ['0','1','2','3'])->default('0')->comment('0 means draft, 1 means pending, 2 means approved and 3 means rejected from Admin');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
