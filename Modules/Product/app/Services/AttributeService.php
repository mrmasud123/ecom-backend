<?php
namespace Modules\Product\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Product\Models\Attribute;
use Modules\Product\Repositories\Interfaces\AttributeRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class AttributeService
{
    public function __construct(protected AttributeRepositoryInterface $attributeRepository)
    {
    }

    public function store(array $data): Attribute
    {
        return DB::transaction(function () use ($data) {
            $attribute = $this->attributeRepository->create($data);

            $attribute->attributeValues()->createMany(
                $this->prepareValues($data['values'])
            );

            Cache::tags(['attributes'])->flush();

            return $attribute->load('attributeValues');
        });
    }

    public function update(int $id, array $data): Attribute
    {
        $attribute = $this->attributeRepository->find($id);

        if (! $attribute) {
            abort(404, 'Attribute not found.');
        }

        return DB::transaction(function () use ($attribute, $data) {
            $attribute = $this->attributeRepository->update($attribute, $data);

            // delete-then-recreate strategy, consistent with your PO line-item approach
            $attribute->attributeValues()->delete();
            $attribute->attributeValues()->createMany(
                $this->prepareValues($data['values'])
            );

            Cache::tags(['attributes'])->flush();

            return $attribute->load('attributeValues');
        });
    }

    public function delete(int $id): bool
    {
        $attribute = $this->attributeRepository->find($id);

        if (! $attribute) {
            abort(404, 'Attribute not found.');
        }

        $deleted = $this->attributeRepository->delete($attribute);

        if ($deleted) {
            Cache::tags(['attributes'])->flush();
        }

        return $deleted;
    }

    /**
     * Normalize value rows: auto-slug when missing, drop color_code
     * unless the attribute is a color type.
     */
    protected function prepareValues(array $values): array
    {
        return collect($values)->map(fn ($row) => [
            'value' => $row['value'],
            'slug' => Str::slug($row['slug'] ?? $row['value']),
            'color_code' => $row['color_code'] ?? null,
        ])->all();
    }

    public function getDatatable(Request $request): JsonResponse
    {
        $query = $this->attributeRepository->queryWithValues();

        return DataTables::of($query)
            ->addColumn('values', function ($attribute) {
                if ($attribute->attributeValues->isEmpty()) {
                    return '<span class="text-xs text-gray-400">—</span>';
                }

                return $attribute->attributeValues->map(function ($value) use ($attribute) {
                    $swatch = $attribute->type === 'color' && $value->color_code
                        ? '<span class="h-2.5 w-2.5 rounded-full border border-black/10" style="background-color: ' . e($value->color_code) . '"></span>'
                        : '';

                    return '
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">
                        ' . $swatch . '
                        ' . e($value->value) . '
                    </span>
                ';
                })->implode(' ');
            })
            ->rawColumns(['values'])
            ->make(true);
    }
}
