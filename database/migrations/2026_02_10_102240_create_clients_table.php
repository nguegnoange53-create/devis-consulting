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
        Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('raison_sociale'); // [cite: 29]
    $table->text('adresse'); // [cite: 30]
    $table->string('telephone'); // [cite: 31]
    $table->string('email'); // [cite: 32]
    $table->string('rccm_cc')->nullable(); // [cite: 33]
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
