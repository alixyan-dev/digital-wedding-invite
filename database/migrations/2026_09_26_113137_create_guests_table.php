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
        Schema::create('guests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('invitation_id')
                ->constrained('invitations')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('unique_code', 32)->unique();
            $table->string('send_status', 20)->default('unsent');
            $table->timestamps();
            $table->index(['invitation_id', 'send_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
