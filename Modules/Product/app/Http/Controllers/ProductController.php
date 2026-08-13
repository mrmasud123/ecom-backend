<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\Brand;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Product\Repositories\AttributeRepository;
use Modules\Product\Repositories\BrandRepository;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Product\Services\ProductService;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{


    public function __construct(
        protected ProductService $productService,
        protected ProductRepositoryInterface $products,
        protected AttributeRepository $attributeRepository,
        protected BrandRepository $brandRepository,
        protected CategoryRepository $categoryRepository,
    ) {}

    public function index()
    {
        return view('product::index');
    }

    public function create()
    {
        return view('product::create', [
            'brands' => Brand::where('is_active', true)->get(['id', 'name']),
            'categories' => Category::where('is_active', true)->get(['id', 'name']),
            'attributes' => $this->attributeRepository->queryWithValues()->get()
        ]);
    }

    public function store(StoreProductRequest $request)
    {
//        return $request->all();
        $product = $this->productService->create(
            $request->validated(),
            $request->file('images', [])
        );

        return response()->json([
            'message' => "\"{$product->name}\" was created successfully.",
            'product' => $product,
        ]);
    }

    public function show(Product $product)
    {
//        return view('product::show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['variants.attributeValues', 'categories', 'media']);

        $attributes = $this->attributeRepository->queryWithValues()->get();

        // Flatten: variants -> attributeValues -> attribute_id, deduplicated
        $selectedAttributeIds = $product->variants
            ->flatMap(fn ($variant) => $variant->attributeValues->pluck('attribute_id'))
            ->unique()
            ->values();
//        return [$product];
//        return $product->getFirstMediaUrl('images');
        return view('product::edit', [
            'product' => $product,
            'attributes' => $attributes,
            'brands' => $this->brandRepository->all(),
            'categories' => $this->categoryRepository->all(),
            'selectedAttributeIds' => $selectedAttributeIds,
        ]);
    }

//    public function update(UpdateProductRequest $request, Product $product)
//    {
//        $product = $this->productService->update(
//            $product,
//            $request->validated(),
//            $request->file('images', [])
//        );
//
//        return response()->json([
//            'message' => "\"{$product->name}\" was updated successfully.",
//            'product' => $product,
//        ]);
//    }
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->update($id, $request->validated());

        return response()->json([
            'message' => 'Product updated successfully.',
            'redirect' => route('product.index'),
            'data' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'message' => "\"{$product->name}\" was deleted successfully.",
        ]);
    }

    public function data(Request $request)
    {
        $params = $request->except(['draw', '_']);
        $cacheKey = 'products:datatable:' . md5(json_encode($params));

        $wasCached = Cache::tags(['products'])->has($cacheKey);
        \Log::info($wasCached ? "CACHE HIT: {$cacheKey}" : "CACHE MISS: {$cacheKey}");

        return Cache::tags(['products'])->remember($cacheKey, now()->addMinutes(5), function () {
            return DataTables::eloquent($this->products->queryForDataTable())
                ->addColumn('brand', fn ($product) => $product->brand->name ?? '—')
                ->addColumn('category', function ($product) {
                    if ($product->categories->isEmpty()) {
                        return '<span class="text-xs text-gray-400">—</span>';
                    }

                    $pills = $product->categories->map(function ($category) {
                        return '<span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-300 whitespace-nowrap">'
                            . e($category->name)
                            . '</span>';
                    })->implode('');

                    return '<div class="flex flex-wrap gap-1.5">' . $pills . '</div>';
                })
                ->addColumn('variants', function ($product) {
                        if (! $product->has_variants || $product->variants->isEmpty()) {
                            return '<span class="text-xs text-gray-400">—</span>';
                        }

                        $variants = $product->variants;
                        $totalStock = $variants->sum('quantity');
                        $prices = $variants->pluck('price')->filter()->map(fn ($p) => (float) $p);
                        $minPrice = $prices->min();
                        $maxPrice = $prices->max();
                        $priceRange = $minPrice === $maxPrice
                        ? '৳' . number_format($minPrice, 2)
                        : '৳' . number_format($minPrice, 2) . ' – ৳' . number_format($maxPrice, 2);

                        $rows = $variants->map(function ($variant) {
                        $stockColor = $variant->quantity > 0
                            ? 'text-emerald-600 dark:text-emerald-400'
                            : 'text-red-500 dark:text-red-400';

                        $activeDot = $variant->is_active
                            ? '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>'
                            : '<span class="h-1.5 w-1.5 rounded-full bg-gray-300 dark:bg-gray-600"></span>';

                        return '
                            <div class="flex items-center justify-between gap-3 px-2 py-1 rounded-md hover:bg-gray-50 dark:hover:bg-white/5">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    ' . $activeDot . '
                                    <span class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">' . e($variant->sku) . '</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">৳' . number_format((float) $variant->price, 2) . '</span>
                                    <span class="text-xs font-medium ' . $stockColor . '">' . $variant->quantity . '</span>
                                </div>
                            </div>
                        ';
                    })->implode('');

                    return '
                        <div class="variant-summary">
                            <button type="button" class="toggle-variants inline-flex items-center gap-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/40 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-900/60 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                ' . $variants->count() . ' variant' . ($variants->count() > 1 ? 's' : '') . '
                            </button>

                            <div class="variant-list hidden mt-2 max-h-40 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg divide-y divide-gray-100 dark:divide-gray-800">
                                ' . $rows . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('action', fn ($product) => view('product::partials.action', compact('product'))->render())
                ->filterColumn('brand', function ($query, $keyword) {
                    $query->whereHas('brand', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('categories', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->rawColumns(['action', 'category','variants'])
                ->toJson()
                ->getData(true);
        });
    }
}
