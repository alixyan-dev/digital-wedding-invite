<?php

declare(strict_types=1);

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
        Schema::create('digital_wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('invitation_id')
                ->constrained('invitations')
                ->cascadeOnDelete();

            $table->string('provider');
            $table->string('account_number');
            $table->string('account_holder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_wallets');
    }
};
