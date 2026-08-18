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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'escrow_hold', 'escrow_release', 'dispute_refund']);
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('momo'); // momo, om, wallet
            $table->string('phone_number')->nullable();
            $table->string('reference')->unique();
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->string('description')->nullable();
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
