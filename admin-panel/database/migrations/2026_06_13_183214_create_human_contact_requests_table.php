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
        Schema::create('human_contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('citizen_name')->nullable();
            $table->string('phone', 50);
            $table->string('topic')->nullable();
            $table->text('message');
            $table->string('preferred_channel')->default('WhatsApp');
            $table->string('status')->default('Pendiente');
            $table->text('internal_notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_contact_requests');
    }
};
