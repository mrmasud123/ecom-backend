<?php
namespace Modules\Product\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Product\Models\Brand;
use Modules\Product\Repositories\Interfaces\BrandRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class BrandService
{
    public function __construct(protected BrandRepositoryInterface $brandRepository)
    {
    }

    public function getDatatable(Request $request): JsonResponse
    {
        $query = $this->brandRepository->queryWithProductCount();

        return DataTables::of($query)
            ->addColumn('logo', fn ($brand) => $brand->logo ? asset('storage/' . $brand->logo) : null)
            ->addColumn('products_count', fn ($brand) => $brand->products_count)
            ->rawColumns(['logo'])
            ->make(true);
    }


    public function store(array $data, ?UploadedFile $logo = null): Brand
    {
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $brand = $this->brandRepository->create($data);

        if ($logo) {
            $brand->addMedia($logo)->toMediaCollection('logo');
        }

        return $brand;
    }



    public function update(Brand $brand, array $data, ?UploadedFile $logo = null): Brand
    {
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $shouldRemoveLogo = (bool) ($data['remove_logo'] ?? false);
        unset($data['remove_logo']);

        if ($logo) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $logo->store('brands', 'public');
        } elseif ($shouldRemoveLogo) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = null;
        }
        $this->brandRepository->update($brand, $data);

        return $brand->fresh();
    }
}
