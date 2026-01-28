<?php

use App\Models\Doctor;
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
        Schema::create('doctor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->onDelete('restrict');
            $table->foreignIdFor(Doctor::class, 'doctor_id')
                ->constrained()
                ->onDelete('cascade');
            $table->text('comment');
            $table->unsignedTinyInteger('rating')->comment('1 to 5');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_reviews');
    }
};
