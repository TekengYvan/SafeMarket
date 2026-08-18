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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->unique()->nullable()->after('email');
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending')->after('phone_number');
            $table->string('id_card_photo')->nullable()->after('kyc_status');
            $table->integer('trust_score')->default(100)->after('id_card_photo');
            $table->boolean('is_admin')->default(false)->after('trust_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'kyc_status', 'id_card_photo', 'trust_score', 'is_admin']);
        });
    }
};
