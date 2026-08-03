<?php

namespace Modules\Product\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\Category;

interface CategoryRepositoryInterface
{
    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): bool;

    public function queryForDataTable(): Builder;

    public function allActive();
}
