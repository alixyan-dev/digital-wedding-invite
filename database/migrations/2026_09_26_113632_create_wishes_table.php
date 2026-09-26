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
        Schema::create('wishes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('invitation_id')
                ->constrained('invitations')
                ->cascadeOnDelete();

            $table->foreignUuid('guest_id')
                ->nullable()
                ->constrained('guests')
                ->cascadeOnDelete();

            $table->string('sender_name');
            $table->text('message');
            $table->boolean('is_visible')->default(true);

            $table->unique(['invitation_id', 'guest_id']);
            $table->index(['invitation_id', 'is_visible']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};
