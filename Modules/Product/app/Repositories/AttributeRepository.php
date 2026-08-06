<?php
// Modules/Product/app/Repositories/AttributeRepository.php
namespace Modules\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Attribute;
use Modules\Product\Repositories\Interfaces\AttributeRepositoryInterface;

class AttributeRepository implements AttributeRepositoryInterface
{
    public function queryWithValuesCount(): Builder
    {
        return Attribute::query()->withCount('values')->select('attributes.*');
    }
}
