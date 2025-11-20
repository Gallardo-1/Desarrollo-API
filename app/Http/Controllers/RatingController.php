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

        // Aquí se asume que $request->user() existe gracias al middleware 'auth:sanctum'.
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
}
