<?php

namespace App\Http\Controllers\API;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function index()
    {
        $data = $this->productRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ProductResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $details = [
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
            $product = $this->productRepositoryInterface->store($details);
            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResource($product), 'Product created successfully', 200);
        }catch (\Exception $e){
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = $this->productRepositoryInterface->getById($product->id);
        return ApiResponseClass::sendResponse(new ProductResource($product), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $updateDetails =[
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
            $product = $this->productRepositoryInterface->update($updateDetails,$id);

            DB::commit();
            return ApiResponseClass::sendResponse('Product Update Successful','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
