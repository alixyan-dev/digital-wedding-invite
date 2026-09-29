<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'template_id',
    'slug',
    'guest_token',
    'guest_token_enabled',
    'publish_start_at',
    'publish_end_at',
    'groom_full_name',
    'bride_full_name',
    'groom_nickname',
    'bride_nickname',
    'groom_father_name',
    'groom_mother_name',
    'bride_father_name',
    'bride_mother_name',
    'thumbnail',
    'groom_photo',
    'bride_photo',
    'music_url',
    'created_by',
])]
#[Appends([
    'public_url',
    'status_label',
])]
class Invitation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'publish_start_at' => 'datetime',
        'publish_end_at' => 'datetime',
        'guest_token_enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation): void {
            if (blank($invitation->slug)) {
                $invitation->slug = static::generateUniqueSlug($invitation);
            }

            if (blank($invitation->publish_start_at)) {
                $invitation->publish_start_at = now();
            }

            if (blank($invitation->publish_end_at)) {
                $invitation->publish_end_at = $invitation->publish_start_at->copy()->addMonths(4);
            }

            if ($invitation->guest_token_enabled && blank($invitation->guest_token)) {
                $invitation->guest_token = Str::random(64);
            }

            if (
                blank($invitation->guest_token)
                && filled($invitation->created_by)
                && $invitation->created_by !== $invitation->user_id
            ) {
                $invitation->guest_token = Str::random(64);
                $invitation->guest_token_enabled = true;
            }
        });
    }

    protected static function generateUniqueSlug(Invitation $invitation): string
    {
        $base = trim(Str::slug(
            ($invitation->groom_full_name ?? 'invitation').'-'.
            ($invitation->bride_full_name ?? '')
        ), '-');

        if ($base === '') {
            $base = 'invitation';
        }

        do {
            $slug = $base.'-'.Str::lower(Str::random(6));
        } while (static::withTrashed()->where('slug', $slug)->exists());

        return $slug;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(InvitationEvent::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(InvitationGallery::class)->orderBy('sort_order');
    }

    public function loveStories(): HasMany
    {
        return $this->hasMany(LoveStory::class)->orderBy('sort_order');
    }

    public function digitalWallets(): HasMany
    {
        return $this->hasMany(DigitalWallet::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function quotaHistories(): HasMany
    {
        return $this->hasMany(QuotaHisory::class);
    }

    public function isExpired(): bool
    {
        return $this->publish_end_at->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->isExpired();
    }

    public function isExpiringSoon(int $days = 14): bool
    {
        return ! $this->isExpired() && $this->publish_end_at->lte(now()->addDays($days));
    }

    public function guestUrl(Guest $guest): string
    {
        return url("/i/{$this->slug}/guest/{$guest->unique_code}");
    }

    public function getPublicUrl(): string
    {
        return url("/i/{$this->slug}");
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($this->isExpiringSoon()) {
            return 'Expiring Soon';
        }

        return 'Active';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('publish_end_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('publish_end_at', '<=', now());
    }

    public function scopeExpiringSoon(Builder $query, int $days = 14): Builder
    {
        return $query->whereBetween('publish_end_at', [
            now(),
            now()->addDays($days),
        ]);
    }
}
