<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_disputed')->default(false);
            $table->text('dispute_reason')->nullable();
            $table->enum('dispute_status', ['none', 'pending', 'resolved_to_buyer', 'resolved_to_vendor'])->default('none');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_disputed', 'dispute_reason', 'dispute_status']);
        });
    }
};
