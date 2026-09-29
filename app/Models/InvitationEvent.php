<?php

namespace App\Models;

use App\Enums\InvitationEventType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

#[Fillable([
    'invitation_id',
    'type',
    'date',
    'time',
    'address',
    'map_url',
])]
class InvitationEvent extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'type' => InvitationEventType::class,
        'date' => 'date',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function scopeCeremony(Builder $query): Builder
    {
        return $query->where('type', InvitationEventType::Ceremony->value);
    }

    public function scopeReception(Builder $query): Builder
    {
        return $query->where('type', InvitationEventType::Reception->value);
    }
}
