<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Http\Requests\StoreBrandRequest;
use Modules\Product\Http\Requests\UpdateBrandRequest;
use Modules\Product\Models\Brand;
use Modules\Product\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brandService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product::brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product::brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $this->brandService->store($request->validated(), $request->file('logo'));

        return response()->json(['message' => 'Brand created successfully.']);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
//        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Brand $brand)
    {
        return view('product::brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->brandService->update($brand, $request->validated(), $request->file('logo'));

        return response()->json(['message' => 'Brand updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function data(Request $request): JsonResponse
    {
        return $this->brandService->getDatatable($request);
    }
}
