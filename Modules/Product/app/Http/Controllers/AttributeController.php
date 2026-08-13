<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Http\Requests\StoreAttributeRequest;
use Modules\Product\Services\AttributeService;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected AttributeService $attributeService)
    {
    }

    public function index()
    {
        return view('product::attributes.index');
    }

    public function data(Request $request)
    {
        return $this->attributeService->getDatatable($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product::attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request): JsonResponse
    {
        $attribute = $this->attributeService->store($request->validated());

        return response()->json([
            'message' => 'Attribute created successfully.',
            'redirect' => route('attributes.index'),
            'data' => $attribute,
        ]);
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
}
