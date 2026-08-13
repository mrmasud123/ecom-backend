<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Product\Models\ProductVariant;

class StockMovement extends Model
{
    public $timestamps = true;
    const UPDATED_AT = null; // ledger rows are never updated

    protected $fillable = [
        'warehouse_id', 'product_variant_id', 'type', 'quantity',
        'quantity_before', 'quantity_after', 'reference_type',
        'reference_id', 'note', 'created_by',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
