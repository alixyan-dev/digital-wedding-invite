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
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('template_id')
                ->constrained('templates')
                ->restrictOnDelete();

            $table->string('slug')->unique();

            $table->string('guest_token', 64)->nullable()->unique();
            $table->boolean('guest_token_enabled')->default(false);

            $table->dateTime('publish_start_at');
            $table->dateTime('publish_end_at');

            $table->string('groom_full_name');
            $table->string('bride_full_name');
            $table->string('groom_nickname');
            $table->string('bride_nickname');

            $table->string('groom_father_name');
            $table->string('groom_mother_name');
            $table->string('bride_father_name');
            $table->string('bride_mother_name');

            $table->string('thumbnail');
            $table->string('groom_photo')->nullable();
            $table->string('bride_photo')->nullable();
            $table->string('music_url')->nullable();

            $table->foreignUuid('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('publish_end_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
