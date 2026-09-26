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
        Schema::create('invitation_events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('invitation_id')
                ->constrained('invitations')
                ->cascadeOnDelete();

            $table->string('type', 20);
            $table->date('date');
            $table->time('time')->nullable();
            $table->text('address');
            $table->text('map_url')->nullable();
            $table->timestamps();
            $table->unique(['invitation_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitation_events');
    }
};
