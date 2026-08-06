<?php

// Modules/Product/app/Services/AttributeService.php
namespace Modules\Product\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Product\Repositories\Interfaces\AttributeRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class AttributeService
{
    public function __construct(protected AttributeRepositoryInterface $attributeRepository)
    {
    }

    public function getDatatable(Request $request): JsonResponse
    {
        $query = $this->attributeRepository->queryWithValuesCount();

        return DataTables::of($query)
            ->addColumn('values_count', fn ($attribute) => $attribute->values_count)
            ->make(true);
    }
}
