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
        Schema::create('bot_translation_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('bot_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_translation_key_id')
                  ->constrained('bot_translation_keys')
                  ->onDelete('cascade');
            $table->string('lang', 10);
            $table->text('value');
            $table->timestamps();

            $table->unique(['bot_translation_key_id', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_translations');
        Schema::dropIfExists('bot_translation_keys');
    }
};
