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
        Schema::create('requests', function (Blueprint $column) {
            $column->id();
            $column->string('ticket_id', 10)->unique();
            $column->string('representative_name', 255);
            $column->string('representative_dni', 20);
            $column->string('institution_name', 255);
            $column->string('institution_type', 50);
            $column->text('description');
            $column->string('location', 255);
            $column->string('status', 50)->default('Recibido');
            $column->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
