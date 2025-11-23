<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $products = Product::withAvg('ratings', 'rating')->get();
        $products = $products->map(function ($product) {
            $product->average_rating = round($product->ratings_avg_rating ?? 0, 2);
            unset($product->ratings_avg_rating);
            return $product;
        });
        
        return response()->json([
            'data' => $products,
            'message' => 'Products retrieved successfully with average ratings',
            'status' => 200
        ], 200);

    }

    
    
    public function store(Request $request)
    {
        //
        try{ 
        $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($request->only(['name','description','price']));
        return response()->json([
            'data' => $product,
            'message' => 'Product created successfully',
            'status' => 201
        ], 201);
        }catch(Exception $error){
        return response()->json([
                'message' => 'Error updating product: ' . $error->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
   public function show(Product $product)
{
    
    $product->loadAvg('ratings', 'rating')
            ->loadCount('ratings'); 
            
    $averageRating = round($product->ratings_avg_rating ?? 0, 2);
    
    unset($product->ratings_avg_rating);
    
    return response()->json([
        'data' => $product,
        'average_rating' => $averageRating,
        'ratings_count' => $product->ratings_count, 
        'message' => 'Product retrieved successfully',
        'status' => 200
    ], 200);
}

    
    
    public function update(Request $request, string $id)
    {
        //
        try{
            $product = Product::findorFail($id);
            $product->update($request->all());
            return response()->json([
                'data' => $product,
                'message' => 'Product updated successfully',
                'status' => 200
            ], 200);
        }catch(Exception $error){
            return response()->json([
                'message' => 'Error updating product: ' . $error->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{
            $product = Product::findorFail($id);

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
                'status' => 200
            ], 200);
        }catch(Exception $error){
            return response()->json([
                'message' => 'Error deleting product: ' . $error->getMessage(),
                'status' => 500
            ], 500);
        }   
    }
}