<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductVariant;
use Yajra\DataTables\Facades\DataTables;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product::product-variants.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function data(Request $request)
    {
//        $query = $this->productVariantRepository->queryForDataTable();
        $query= ProductVariant::with(['product', 'attributeValues'])->select('product_variants.*');
        if ($request->filled('stock_status')) {
            $query = match ($request->stock_status) {
                'in_stock' => $query->where('quantity', '>=', 10),
                'low_stock' => $query->whereBetween('quantity', [1, 9]),
                'out_of_stock' => $query->where('quantity', '<=', 0),
                default => $query,
            };
        }

        return DataTables::eloquent($query)
            ->addColumn('variant', function ($variant) {
                $thumb = $variant->product->getFirstMediaUrl('images') ?: null;
                $avatar = $thumb
                    ? '<img src="' . e($thumb) . '" class="h-9 w-9 rounded-lg object-cover">'
                    : '<span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 text-xs">—</span>';

                return '
                <div class="flex items-center gap-3">
                    ' . $avatar . '
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white">' . e($variant->product->name) . '</p>
                        <p class="text-xs text-gray-400">Variant #' . $variant->id . '</p>
                    </div>
                </div>
            ';
            })
            ->addColumn('sku', fn ($variant) => '<span class="font-mono text-xs text-gray-600 dark:text-gray-300">' . e($variant->sku) . '</span>')
            ->addColumn('combination', function ($variant) {
                if ($variant->attributeValues->isEmpty()) {
                    return '<span class="text-xs text-gray-400">—</span>';
                }

                return $variant->attributeValues->map(function ($value) {
                    return '<span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/40 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 mr-1">' . e($value->value) . '</span>';
                })->implode('');
            })
            ->addColumn('price', function ($variant) {
                $price = $variant->price ?? $variant->product->price;
                $inherited = $variant->price === null;

                $label = '৳' . number_format($price, 2);
                if ($inherited) {
                    $label .= ' <span class="text-xs text-gray-400">(inherited)</span>';
                }

                return '<span class="font-medium text-gray-700 dark:text-gray-300">' . $label . '</span>';
            })
            ->addColumn('stock', function ($variant) {
                $qty = $variant->quantity;
                $color = $qty <= 0
                    ? 'text-red-600 dark:text-red-400'
                    : ($qty < 10 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400');

                return '<span class="font-semibold ' . $color . '">' . $qty . '</span> <span class="text-xs text-gray-400">units</span>';
            })
            ->addColumn('status', function ($variant) {
                return $variant->is_active
                    ? '<span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-300">Active</span>'
                    : '<span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-500 dark:text-gray-400">Inactive</span>';
            })
            ->addColumn('action', function ($variant) {
                return '<a href="' . route('product.edit', $variant->product_id) . '"
                class="inline-flex items-center justify-center h-8 w-8 rounded-md text-gray-500 hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-blue-400 transition-colors"
                title="Edit on product page">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>';
            })
            ->filterColumn('sku', fn ($query, $keyword) => $query->where('sku', 'like', "%{$keyword}%"))
            ->rawColumns(['variant', 'sku', 'combination', 'price', 'stock', 'status', 'action'])
            ->toJson()
            ->getData(true);
    }

    public function grouped(Request $request)
    {
        $query = Product::query()
            ->where('has_variants', true)
            ->with(['variants.attributeValues']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('variants', fn ($v) => $v->where('sku', 'like', "%{$search}%")));
        }

        $products = $query->get()->map(function ($product) use ($request) {
            $variants = $product->variants;

            if ($request->stock_status === 'low_stock') {
                $variants = $variants->filter(fn ($v) => $v->quantity > 0 && $v->quantity < 10);
            } elseif ($request->stock_status === 'out_of_stock') {
                $variants = $variants->filter(fn ($v) => $v->quantity <= 0);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'variants' => $variants->values()->map(fn ($v) => [
                    'sku' => $v->sku,
                    'price' => $v->price,
                    'quantity' => $v->quantity,
                    'attribute_values' => $v->attributeValues->pluck('value'),
                ]),
            ];
        })->filter(fn ($p) => $p['variants']->isNotEmpty())->values();

        return response()->json($products);
    }
    public function stats(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'total' => \Modules\Product\Models\ProductVariant::count(),
            'active' => \Modules\Product\Models\ProductVariant::where('is_active', true)->count(),
            'low_stock' => \Modules\Product\Models\ProductVariant::whereBetween('quantity', [1, 9])->count(),
            'out_of_stock' => \Modules\Product\Models\ProductVariant::where('quantity', '<=', 0)->count(),
        ]);
    }
}
