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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained();
            $table->string('name', 50);
            $table->integer('amount');
            $table->text('description');
            $table->foreignId('currency_id')->constrained();
            $table->date('time_expire');
            $table->string('billing_frequency', 10);
            $table->string('status', 45);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
