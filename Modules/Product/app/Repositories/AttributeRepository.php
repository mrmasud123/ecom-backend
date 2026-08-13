<?php
namespace Modules\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Attribute;
use Modules\Product\Repositories\Interfaces\AttributeRepositoryInterface;

class AttributeRepository implements AttributeRepositoryInterface
{
    public function __construct(protected Attribute $model)
    {
    }

//    public function queryForDataTable(): Builder
//    {
//        return $this->model->withCount('attributeValues')->select('attributes.*');
//    }

    public function create(array $data): Attribute
    {
        return $this->model->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'type' => $data['type'],
        ]);
    }

    public function update(Attribute $attribute, array $data): Attribute
    {
        $attribute->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'type' => $data['type'],
        ]);

        return $attribute->fresh();
    }

    public function find(int $id): ?Attribute
    {
        return $this->model->with('values')->find($id);
    }

    public function delete(Attribute $attribute): bool
    {
        return (bool) $attribute->delete();
    }

    public function queryWithValues(): Builder
    {
        return Attribute::query()
            ->select('attributes.*')
            ->with('attributeValues');
    }

}
