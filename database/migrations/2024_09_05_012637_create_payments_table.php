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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 50);
            $table->string('email');
            $table->integer('value');
            $table->json('fields')->nullable();
            $table->integer('num_document');
            $table->string('type_document', length: 3);
            $table->foreignId('microsite_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->foreignId('subscription_id')->nullable()->constrained();
            $table->string('reference');
            $table->integer('request_id')->nullable();
            $table->string('process_url', 255)->nullable();
            $table->string('payment_type', 255);
            $table->string('status', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
