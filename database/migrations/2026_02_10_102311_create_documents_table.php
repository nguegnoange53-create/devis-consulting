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
        Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['devis', 'facture', 'proforma']); // [cite: 15, 16]
    $table->string('numero')->unique(); // Numérotation auto [cite: 48, 65]
    $table->foreignId('client_id')->constrained()->onDelete('cascade'); // [cite: 13]
    $table->date('date_emission');
    $table->decimal('taux_tva', 5, 2); // [cite: 52]
    $table->string('statut'); // Brouillon, Payé, etc. [cite: 55, 69]
    $table->decimal('total_ht', 15, 2); // [cite: 51, 67]
    $table->decimal('total_tva', 15, 2); // [cite: 52, 67]
    $table->decimal('total_ttc', 15, 2); // [cite: 53, 67]
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
