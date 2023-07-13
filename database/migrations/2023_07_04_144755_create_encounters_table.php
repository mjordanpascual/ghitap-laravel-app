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
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_number');
            $table->datetime('timestamp');
            $table->string('department_code')->nullable();
            $table->enum('type', ['R', 'T'])->nullable();
            $table->timestamps();

            $table->foreign('hospital_number')->references('hospital_number')->on('patients');
            $table->foreign('department_code')->references('code')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
