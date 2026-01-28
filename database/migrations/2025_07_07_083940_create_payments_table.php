<?php

use App\Models\Appointment;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Appointment::class, 'appointment_id')
                ->constrained()
                ->onDelete('restrict');
            $table->string('transaction_id');
            $table->integer('amount');
            $table->enum('status', ['pending', 'paid', 'canceled']);
            $table->dateTime('paid_at');
            $table->string('gateway');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
