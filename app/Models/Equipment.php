<?php

namespace App\Models;

use App\Enums\EquipmentStatus;
use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    "codigo_patrimonial",
    "marca",
    "modelo",
    "cpu",
    "ram",
    "almacenamiento",
    "estado",
    "category_id",
])]
class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = "equipments";

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "estado" => EquipmentStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
