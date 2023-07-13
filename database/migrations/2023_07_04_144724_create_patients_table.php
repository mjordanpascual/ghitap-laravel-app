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
        Schema::create('patients', function (Blueprint $table) {
            $table->string('hospital_number')->primary();
            $table->string('patlast');
            $table->string('patfirst');
            $table->string('patmiddle')->nullable();
            $table->enum('patsuffix', ['Jr', 'Sr', 'II', 'III', 'IV', 'V'])->nullable();
            $table->date('patbdate');
            $table->enum('patsex', ['M', 'F']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
