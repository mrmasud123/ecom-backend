<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Models\Brand;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Product\Services\ProductService;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductRepositoryInterface $products,
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
//        return view('product::edit', [
//            'product' => $product->load('categories', 'media'),
//            'brands' => Brand::where('is_active', true)->get(['id', 'name']),
//            'categories' => Category::where('is_active', true)->get(['id', 'name']),
//        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update(
            $product,
            $request->validated(),
            $request->file('images', [])
        );

        return response()->json([
            'message' => "\"{$product->name}\" was updated successfully.",
            'product' => $product,
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
        $cacheKey = 'products:datatable:' . md5(json_encode($request->all()));

        return Cache::tags(['products'])->remember($cacheKey, now()->addMinutes(5), function () {
            return DataTables::eloquent($this->products->queryForDataTable())
                ->addColumn('brand', fn ($product) => $product->brand->name ?? '—')
                ->addColumn('category', fn ($product) => $product->categories->pluck('name')->implode(', ') ?: '—')
                ->addColumn('productType', fn ($product) => $product->has_variants ? 'Variant' : 'Simple')
                ->addColumn('variants_count', fn ($product) => $product->variants_count)
                ->addColumn('action', fn ($product) => view('product::partials.action', compact('product'))->render())
                ->filterColumn('brand', function ($query, $keyword) {
                    $query->whereHas('brand', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('categories', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->rawColumns(['action'])
                ->toJson()
                ->getData(true);
        });
    }
}
