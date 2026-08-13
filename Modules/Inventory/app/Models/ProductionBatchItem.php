<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\ProductVariant;

class ProductionBatchItem extends Model
{
    protected $fillable = [
        'production_batch_id', 'product_variant_id',
        'quantity_produced', 'unit_cost',
    ];

    protected $casts = [
        'quantity_produced' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    public function productionBatch(): BelongsTo
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getLineTotalAttribute(): float
    {
        return (float) $this->quantity_produced * (float) $this->unit_cost;
    }
}
