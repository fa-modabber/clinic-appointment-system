<?php

use App\Enums\AppointmentStatus;
use App\Models\Doctor;
use App\Models\TimeSlot;
use App\Models\Patient;
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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignIdFor(Patient::class, 'patient_id')
                ->constrained('users')
                ->onDelete('restrict');
            $table->foreignIdFor(Doctor::class, 'doctor_id')
                ->constrained()
                ->onDelete('restrict');
            $table->date('date');
            $table->time('start_time');
            $table->enum(
                'status',
                array_column(AppointmentStatus::cases(), 'value')
            )->default(AppointmentStatus::PENDING->value);
            $table->dateTime('cancelled_at')->nullable();
            $table->enum('type', ['online', 'in-site']);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['doctor_id', 'start_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
