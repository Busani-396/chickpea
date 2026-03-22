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
        Schema::create('duplicate_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempted_user_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('payload')->nullable(); 
            $table->timestamps();
            $table->index(['attempted_user_id', 'campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duplicate_reports');
    }
};
