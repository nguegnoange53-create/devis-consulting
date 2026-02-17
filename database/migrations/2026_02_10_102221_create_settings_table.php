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
        Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('nom_entreprise'); // [cite: 75]
    $table->string('logo')->nullable(); // [cite: 76]
    $table->text('adresse'); // [cite: 77]
    $table->string('telephone'); // [cite: 78]
    $table->string('email'); // [cite: 79]
    $table->string('rccm_cc')->nullable(); // [cite: 80]
    $table->decimal('tva_defaut', 5, 2)->default(18.00); // [cite: 81]
    $table->string('devise')->default('FCFA'); // [cite: 82]
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
