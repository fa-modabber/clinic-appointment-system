<?php

use App\Models\City;
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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();
            $table->foreignId('clinic_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('medical_code')->unique();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->string('education')->nullable();
            $table->string('image')->nullable();
            $table->unsignedTinyInteger('booking_days_ahead')->default(7);
            $table->unsignedBigInteger('visit_price');
            $table->unsignedTinyInteger('visit_duration');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
