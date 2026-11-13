<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    "user_id",
    "office",
    "category_id",
    "quantity",
    "status",
    "rejection_reason",
])]
class Request extends Model
{
    public const PENDING_STATUS = "pendiente";

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "status" => RequestStatus::class,
            "quantity" => "integer",
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<RequestItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    public function isPending(): bool
    {
        return $this->status === RequestStatus::Pendiente;
    }
}
