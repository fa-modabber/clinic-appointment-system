<?php

use App\Models\Doctor;
use App\Models\TimeSlot;
use App\Models\User;
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
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->onDelete('restrict');
            $table->foreignIdFor(Doctor::class, 'doctor_id')
                ->constrained()
                ->onDelete('restrict');
            $table->foreignIdFor(TimeSlot::class, 'time_slot_id')
                ->constrained()
                ->onDelete('restrict');
            $table->enum('status', ['pending', 'paid', 'canceled','completed']);
            $table->dateTime('cancelled_at');
            $table->timestamps();
            $table->softDeletes();
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
