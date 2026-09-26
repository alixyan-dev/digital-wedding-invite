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
        Schema::create('quota_hisories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignUuid('invitation_id')
                ->nullable()
                ->constrained('invitations')
                ->nullOnDelete();

            $table->string('type', 20);
            $table->unsignedInteger('amount');
            $table->unsignedInteger('balance_after');
            $table->text('note')->nullable();

            $table->index(['user_id', 'type', 'created_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_hisories');
    }
};
