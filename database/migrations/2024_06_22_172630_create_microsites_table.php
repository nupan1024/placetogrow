<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsites_type_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->string('name', length: 50);
            $table->index('name');
            $table->string('logo_path')->nullable()->default(null);
            $table->foreignId('currency_id')->constrained();
            $table->text('description');
            $table->boolean('status');
            $table->json('fields')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
