<?php

use App\Models\Doctor;
use App\Models\Specialty;
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
        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table->foreignIdFor(Doctor::class, 'doctor_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Specialty::class, 'specialty_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();

            $table->primary(['doctor_id', 'Specialty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_specialty');
    }
};
