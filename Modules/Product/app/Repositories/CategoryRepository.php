<?php

namespace Modules\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Category;
use Modules\Product\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(protected Category $model) {}

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function queryForDataTable(): Builder
    {
        return $this->model->newQuery()
            ->select(['categories.id', 'categories.name', 'categories.parent_id', 'categories.sort_order', 'categories.is_active'])
            ->with('parent:id,name')
            ->withCount('children');
    }

    public function allActive()
    {
        return $this->model->where('is_active', true)->orderBy('name')->get(['id', 'name', 'parent_id']);
    }
}
