<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Database\Factories\SupplierFactory;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'tax_number',
        'payment_terms_days',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_terms_days' => 'integer',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\Modules\Inventory\Models\PurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

//    protected static function newFactory(): SupplierFactory
//    {
//        return SupplierFactory::new();
//    }
}
