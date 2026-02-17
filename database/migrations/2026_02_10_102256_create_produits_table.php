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
       Schema::create('produits', function (Blueprint $table) {
    $table->id();
    $table->string('designation'); // [cite: 39]
    $table->decimal('prix_unitaire_ht', 15, 2); // [cite: 40]
    $table->enum('type', ['produit', 'service']); // [cite: 42]
    $table->text('description')->nullable(); // [cite: 43]
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
