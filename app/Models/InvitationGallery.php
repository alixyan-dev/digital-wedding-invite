<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'invitation_id',
    'image',
    'sort_order',
])]
class InvitationGallery extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function invitations(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
