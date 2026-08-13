<?php

namespace Modules\Marketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Marketing\Http\Requests\StoreDiscountRequest;
use Modules\Marketing\Http\Requests\UpdateDiscountRequest;
use Modules\Marketing\Models\Discount;
use Modules\Marketing\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Marketing\Services\Interfaces\DiscountServiceInterface;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Product\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Product\Services\CategoryService;
use Modules\Product\Services\ProductService;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(
        protected DiscountServiceInterface $discountService,
        protected DiscountRepositoryInterface $discountRepository,
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
    }
    public function index()
    {
        return view('marketing::discount-campaign.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marketing::discount-campaign.create', [
            'products' => Product::all(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('marketing::show');
    }

    public function stats(): \Illuminate\Http\JsonResponse
    {
        $now = now();

        $active = Discount::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->count();

        $scheduled = Discount::query()
            ->where('is_active', true)
            ->where('starts_at', '>', $now)
            ->count();

        $expired = Discount::query()
            ->where('ends_at', '<', $now)
            ->count();

        return response()->json([
            'active' => $active,
            'scheduled' => $scheduled,
            'expired' => $expired,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function data(Request $request)
    {
        $query = $this->discountRepository->queryForDataTable()->withCount('discountables');

        return DataTables::eloquent($query)
            ->addColumn('campaign', function ($discount) {
                $icon = $discount->type === 'percentage'
                    ? '<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-xs font-bold">%</span>'
                    : '<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-xs font-bold">৳</span>';

                return '
                <div class="flex items-center gap-3">
                    ' . $icon . '
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white">' . e($discount->name) . '</p>
                        <p class="text-xs text-gray-400">' . e($discount->slug) . '</p>
                    </div>
                </div>
            ';
            })
            ->addColumn('value', fn ($discount) => $discount->type === 'percentage'
                ? '<span class="font-semibold text-gray-700 dark:text-gray-300">' . rtrim(rtrim($discount->value, '0'), '.') . '% off</span>'
                : '<span class="font-semibold text-gray-700 dark:text-gray-300">৳' . number_format($discount->value, 2) . ' off</span>')
            ->addColumn('target', function ($discount) {
                if ($discount->discountables_count === 0) {
                    return '<span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/40 px-2.5 py-1 text-xs font-medium text-purple-700 dark:text-purple-300">Storewide</span>';
                }

                $type = optional($discount->discountables->first())->discountable_type;
                $label = match ($type) {
                    'Modules\Product\Models\Product' => 'product',
                    'Modules\Product\Models\ProductVariant' => 'variant',
                    'Modules\Category\Models\Category' => 'category',
                    default => 'item',
                };
                $count = $discount->discountables_count;

                return '<span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/40 px-2.5 py-1 text-xs font-medium text-blue-700 dark:text-blue-300">'
                    . $count . ' ' . $label . ($count > 1 ? 's' : '') . '</span>';
            })
            ->addColumn('schedule', function ($discount) {
                if (! $discount->starts_at && ! $discount->ends_at) {
                    return '<span class="text-xs text-gray-400">No end date</span>';
                }

                $starts = $discount->starts_at?->format('M j, Y') ?? 'Now';
                $ends = $discount->ends_at?->format('M j, Y') ?? 'Ongoing';

                return '<span class="text-xs text-gray-500 dark:text-gray-400">' . $starts . ' &rarr; ' . $ends . '</span>';
            })
            ->addColumn('status', function ($discount) {
                $now = now();
                $started = ! $discount->starts_at || $discount->starts_at <= $now;
                $notEnded = ! $discount->ends_at || $discount->ends_at >= $now;

                if (! $discount->is_active) {
                    $label = 'Paused'; $classes = 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400';
                } elseif (! $started) {
                    $label = 'Scheduled'; $classes = 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300';
                } elseif (! $notEnded) {
                    $label = 'Expired'; $classes = 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300';
                } else {
                    $label = 'Active'; $classes = 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300';
                }

                return '<span class="inline-flex items-center gap-1.5 rounded-full ' . $classes . ' px-2.5 py-1 text-xs font-medium">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>' . $label . '</span>';
            })
            ->addColumn('action', fn ($discount) => view('marketing::discount-campaign.partials.action', compact('discount'))->render())
            ->rawColumns(['campaign', 'value', 'target', 'schedule', 'status', 'action'])
            ->toJson()
            ->getData(true);
    }



    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = $this->discountService->store($request->validated());

        return response()->json([
            'message' => 'Discount campaign launched successfully.',
            'redirect' => route('discount.index'),
            'data' => $discount,
        ]);
    }

    public function edit(int $id)
    {
        $discount = $this->discountRepository->find($id);

        if (! $discount) {
            abort(404);
        }

        $discount->load('discountables');

        return view('marketing::discount.edit', [
            'discount' => $discount,
            'products' => $this->productRepository->all(),
            'categories' => $this->categoryRepository->all(),
        ]);
    }

    public function update(UpdateDiscountRequest $request, int $id): JsonResponse
    {
        $discount = $this->discountService->update($id, $request->validated());

        return response()->json([
            'message' => 'Discount updated successfully.',
            'redirect' => route('discount.index'),
            'data' => $discount,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->discountService->delete($id);

        return response()->json(['message' => 'Discount deleted successfully.']);
    }
}
