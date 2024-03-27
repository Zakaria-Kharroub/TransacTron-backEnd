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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
        $table->uuid('numero_compte_sender'); 
        $table->foreign('numero_compte_sender')->references('numero_compte')->on('comptes');
        $table->uuid('numero_compte_receiver'); 
        $table->foreign('numero_compte_receiver')->references('numero_compte')->on('comptes');
        $table->double('montant');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
