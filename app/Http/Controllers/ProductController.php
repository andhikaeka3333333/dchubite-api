<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // public function index()
    // {
    //     $products = Product::all();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Products retrieved successfully',
    //         'data' => ProductResource::collection($products)
    //     ], 200);
    // }
    public function index()
    {
        $products = Product::where('status', 'active')->get();

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => ProductResource::collection($products)
        ], 200);
    }

    public function getInactive()
    {
        $products = Product::where('status', 'inactive')->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No inactive products found',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($products),
        ]);
    }



    public function activate($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->update(['status' => 'active']);

        return response()->json([
            'status' => true,
            'message' => 'Product activated successfully',
            'data' => new ProductResource($product)
        ], 200);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = null;
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'cost_price' => $request->cost_price,
            'image' => $imagePath ? asset('storage/' . $imagePath) : null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload image baru jika ada, hapus yang lama
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete(str_replace(asset('storage/'), '', $product->image));
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = asset('storage/' . $imagePath);
        }

        $product->update($request->except('image'));

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    // public function destroy($id)
    // {
    //     $product = Product::find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Product not found'
    //         ], 404);
    //     }

    //     if ($product->image) {
    //         Storage::disk('public')->delete(str_replace(asset('storage/'), '', $product->image));
    //     }

    //     $product->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product deleted successfully'
    //     ], 200);
    // }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Update status menjadi inactive
        $product->update(['status' => 'inactive']);

        return response()->json([
            'status' => true,
            'message' => 'Product deactivated successfully'
        ], 200);
    }
}
