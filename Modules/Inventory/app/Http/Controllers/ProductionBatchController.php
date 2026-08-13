<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Http\Requests\StoreProductionBatchRequest;
use Modules\Inventory\Http\Requests\UpdateProductionBatchRequest;
use Modules\Inventory\Models\ProductionBatch;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\ProductionBatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionBatchController extends Controller
{
    public function __construct(
        private readonly ProductionBatchService $service
    ) {}

    public function index(): View
    {
        return view('inventory::production-batches.index');
    }

    public function data(Request $request): JsonResponse
    {
        return $this->service->getForDataTable()->toJson();
    }

    public function create(): View
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $nextBatchNumber = $this->service->nextBatchNumber();

        return view('inventory::production-batches.create', compact('warehouses', 'nextBatchNumber'));
    }

    public function store(StoreProductionBatchRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('items');
        $items = $request->validated('items');

        $batch = $this->service->create($data, $items);

        return redirect()
            ->route('production-batches.edit', $batch)
            ->with('success', 'Batch saved as draft. Review it and click "Complete" to add stock.');
    }

    public function edit(ProductionBatch $productionBatch): View
    {
        $warehouses = Warehouse::active()->orderBy('name')->get();
        $batch = $productionBatch->load('items.productVariant');

        return view('inventory::production-batches.edit', [
            'batch' => $batch,
            'warehouses' => $warehouses,
        ]);
    }

    public function update(UpdateProductionBatchRequest $request, ProductionBatch $productionBatch): RedirectResponse
    {
        try {
            $data = $request->safe()->except('items');
            $items = $request->validated('items');

            $this->service->update($productionBatch, $data, $items);

            return redirect()
                ->route('production-batches.edit', $productionBatch)
                ->with('success', 'Batch updated.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ProductionBatch $productionBatch): JsonResponse
    {
        try {
            $this->service->delete($productionBatch);

            return response()->json(['success' => true, 'message' => 'Batch deleted.']);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function complete(ProductionBatch $productionBatch): JsonResponse
    {
        try {
            $batch = $this->service->complete($productionBatch);

            return response()->json([
                'success' => true,
                'message' => "Batch {$batch->batch_number} completed. Stock has been updated.",
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
