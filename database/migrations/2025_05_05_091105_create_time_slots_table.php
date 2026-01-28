<?php

use App\Models\Doctor;
use App\Models\DoctorSchedule;
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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Doctor::class, 'doctor_id')
                ->constrained()
                ->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->boolean('is_reserved');
            $table->foreignIdFor(DoctorSchedule::class, 'schedule_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
