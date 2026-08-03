<?php

namespace Modules\Product\Services;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Product\Models\Category;
use Modules\Product\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categories) {}

    public function create(array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? $data['name']);

        return $this->categories->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (! empty($data['parent_id'])) {
            $this->guardAgainstCircularParent($category, (int) $data['parent_id']);
        }

        if (empty($data['slug']) && $category->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        return $this->categories->update($category, $data);
    }

    public function delete(Category $category): bool
    {
        return $this->categories->delete($category);
    }

    /**
     * Prevent assigning a category's own descendant as its parent, which would
     * create a circular chain (e.g. Category A → child B → grandchild C → back to A).
     */
    protected function guardAgainstCircularParent(Category $category, int $proposedParentId): void
    {
        $current = Category::find($proposedParentId);

        while ($current) {
            if ($current->id === $category->id) {
                throw ValidationException::withMessages([
                    'parent_id' => 'A category cannot be moved under one of its own subcategories.',
                ]);
            }
            $current = $current->parent;
        }
    }

    protected function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $counter = 2;

        while (
        Category::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
