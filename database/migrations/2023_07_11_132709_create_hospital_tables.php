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
        Schema::create('departments', function (Blueprint $table) {
            $table->string('code')->unique();
            $table->string('name');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->string('hospital_number')->primary();
            $table->string('patlast');
            $table->string('patfirst');
            $table->string('patmiddle')->nullable();
            $table->enum('patsuffix', ['Jr', 'Sr', 'II', 'III', 'IV', 'V'])->nullable();
            $table->date('patbdate');
            $table->enum('patsex', ['M', 'F']);
        });

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
        Schema::dropIfExists('hospital_tables');
    }
};
