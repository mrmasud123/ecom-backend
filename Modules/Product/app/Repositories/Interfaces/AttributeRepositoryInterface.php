<?php
// Modules/Product/app/Repositories/Interfaces/AttributeRepositoryInterface.php
namespace Modules\Product\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface AttributeRepositoryInterface
{
    public function queryWithValuesCount(): Builder;
}
