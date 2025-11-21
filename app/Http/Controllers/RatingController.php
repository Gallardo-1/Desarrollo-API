<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Almacena o actualiza una valoración para un producto (Requiere Autenticación).
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5', 
        ]);
        $user = $request->user();
        
        $rating = Rating::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => $user->id],
            ['rating' => $request->rating]
        );

        return response()->json([
            'data' => $rating,
            'message' => 'Rating submitted/updated successfully',
            'status' => 201
        ], 201);
    }

    /**
     * Obtiene el producto con la mejor valoración promedio.
     */
    public function bestRatedProduct()
    {
        $bestProduct = Product::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->first();

        if (!$bestProduct) {
            return response()->json([
                'message' => 'No products found or no ratings available.',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'data' => $bestProduct,
            'average_rating' => round($bestProduct->ratings_avg_rating ?? 0, 2),
            'message' => 'Best rated product retrieved successfully',
            'status' => 200
        ], 200);
    }

    public function showProductRating(Product $product)
    {
        $product->loadAvg('ratings', 'rating')
                ->loadCount('ratings');

        return response()->json([
            'product_id' => $product->id,
            'average_rating' => round($product->ratings_avg_rating ?? 0, 2),
            'rating_count' => $product->ratings_count,
            'message' => 'Product rating statistics retrieved successfully',
            'status' => 200
        ], 200);
    }

    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();

        $deleted = Rating::where('product_id', $product->id)
                          ->where('user_id', $user->id)
                          ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Rating deleted successfully',
                'status' => 200
            ], 200);
        }
        return response()->json([
            'message' => 'No rating found for this user and product to delete.',
            'status' => 404
        ], 404);
    }
}
