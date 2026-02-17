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
        Schema::create('document_lignes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id')->constrained()->onDelete('cascade');
    $table->string('designation'); // On stocke le nom au cas où le produit change plus tard
    $table->integer('quantite'); // [cite: 41]
    $table->decimal('prix_unitaire', 15, 2); 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_lignes');
    }
};
