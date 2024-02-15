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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tool_name')->nullable();
            $table->string('tool_link')->nullable();
            $table->string('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('tool_image')->nullable();
            $table->enum('status', ['0','1'])->default('0')->comment('0 means pending 1 means approved from Admin');
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
