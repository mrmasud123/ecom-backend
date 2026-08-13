<?php
// Modules/Product/app/Repositories/Interfaces/AttributeRepositoryInterface.php
namespace Modules\Product\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Attribute;

interface AttributeRepositoryInterface
{
    public function queryWithValues(): Builder;

//    public function queryForDataTable();

    public function create(array $data): Attribute;

    public function update(Attribute $attribute, array $data): Attribute;

    public function find(int $id): ?Attribute;

    public function delete(Attribute $attribute): bool;
}
