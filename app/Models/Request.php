<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'office', 'category_id', 'quantity', 'status', 'rejection_reason'])]
class Request extends Model
{
    /**
     * Request status constant for 'pending'.
     */
    public const PENDING_STATUS = 'pendiente';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RequestStatus::class,
            'quantity' => 'integer',
        ];
    }

    /**
     * The requester (Jefe de Oficina) who created the request.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category of equipment requested.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Equipment assigned to this request.
     *
     * @return HasMany<RequestItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Determine if the request is still waiting for approval.
     */
    public function isPending(): bool
    {
        return $this->status === RequestStatus::Pendiente;
    }
}
