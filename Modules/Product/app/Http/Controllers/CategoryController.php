<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Http\Requests\StoreCategoryRequest;
use Modules\Product\Http\Requests\UpdateCategoryRequest;
use Modules\Product\Models\Category;
use Modules\Product\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Product\Services\CategoryService;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryRepositoryInterface $categories,
    ) {}

    public function index()
    {
        return view('product::categories.index');
    }

    public function create()
    {
        return view('product::categories.create', [
            'parents' => $this->categories->allActive(),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());

        return response()->json([
            'message' => "\"{$category->name}\" was created successfully.",
        ]);
    }

    public function edit(Category $category)
    {
        return view('product::categories.edit', [
            'category' => $category,
            'parents' => $this->categories->allActive()->reject(fn ($c) => $c->id === $category->id),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryService->update($category, $request->validated());

        return response()->json([
            'message' => "\"{$category->name}\" was updated successfully.",
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'This category has subcategories. Move or delete them first.',
            ], 422);
        }

        $this->categoryService->delete($category);

        return response()->json(['message' => "\"{$category->name}\" was deleted successfully."]);
    }

    public function data(Request $request)
    {
        $cacheKey = 'categories:datatable:' . md5(json_encode($request->all()));

        return Cache::tags(['categories'])->remember($cacheKey, now()->addMinutes(5), function () {
            return DataTables::eloquent($this->categories->queryForDataTable())
                ->addColumn('parent', fn ($category) => $category->parent->name ?? '— (top level)')
                ->addColumn('children_count', fn ($category) => $category->children_count)
                ->addColumn('status', fn ($category) => $category->is_active
                    ? '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Active</span>'
                    : '<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">Inactive</span>')
                ->addColumn('action', fn ($category) => view('product::categories.partials.actions', compact('category'))->render())
                ->filterColumn('parent', function ($query, $keyword) {
                    $query->whereHas('parent', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->rawColumns(['status', 'action'])
                ->toJson()
                ->getData(true);
        });
    }
}
