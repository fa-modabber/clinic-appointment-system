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
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_token')->unique();
            $table->string('mobile', 11)->index();
            $table->string('code_hash');
            $table->string('context')->index();
            // examples:
            // login
            // forget-password
            $table->ipAddress('ip_address')->nullable();
            $table->dateTime('used_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();
            $table->index(['mobile', 'context']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
