<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Exception;


class RatingController extends Controller
{
    /**
     * Almacena o actualiza una valoración para un producto (Requiere Autenticación).
     */
   public function store(Request $request, $product_id)
{
        try {
        // Validación
        $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        ]);

            $user = $request->user();

            // Verificar si ya existe valoración del usuario
            $exists = Rating::where('user_id', $user->id)
                    ->where('product_id', $product_id)
                    ->exists();

        if ($exists) {
         return response()->json([
            'message' => 'Ya valoraste este producto. Usa UPDATE para modificar tu calificación.',
            'status' => 409
        ], 409);
    }

         $rating = Rating::create([
        'rating'     => $request->rating,
        'product_id' => $product_id,
        'user_id'    => $user->id,
    ]);

         return response()->json([
        'message' => 'Valoración creada correctamente',
        'data'    => $rating,
        'status'  => 201
    ], 201);
    
}   catch (\Exception $error) {
    return response()->json([
        'error'  => $error->getMessage(),
        'status' => 500
    ], 500);
}

}



    /**
     * Obtiene el producto con la mejor valoración promedio.
     */
    public function bestRatedProduct()
{
        try {
            $bestProduct = Product::withAvg('ratings as average_rating', 'rating')
            ->withCount('ratings')
            ->whereHas('ratings')               // Solo productos con calificaciones
            ->orderByDesc('average_rating')
            ->first();

        if (!$bestProduct) {
        return response()->json([
            'message' => 'No hay productos calificados disponibles.',
            'status' => 404
        ], 404);
    }

        return response()->json([
        'data' => $bestProduct,
        'average_rating' => round($bestProduct->average_rating, 2),
        'total_ratings' => $bestProduct->ratings()->count(),
        'message' => 'Mejor producto calificado obtenido exitosamente',
        'status' => 200
     ], 200);

        } catch (Exception $error) {
        return response()->json([
        'error' => 'Error al obtener el mejor producto calificado',
        'status' => 500
        ], 500);
}


}

//mostrar las estadisticas de un producto 
    public function showProductRating(Product $product)
{
    try {
        $product->loadAvg('ratings as average_rating', 'rating')
                ->loadCount('ratings');

        return response()->json([
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name, 
                'average_rating' => round($product->average_rating ?? 0, 2),
                'rating_count' => $product->ratings_count,
            ],
            'message' => 'estadisticas del producto obtenidas correctamente',
            'status' => 200
        ], 200);

    } catch (Exception $error) {
        return response()->json([
            'error' => 'error al obtener las estadisticas',
            'status' => 500
        ], 500);
    }
}


public function update(Request $request, $product_id)
{
        try {
        $request->validate([
        'rating' => 'required|integer|min:1|max:5'
        ]);

    $user = $request->user();

    // Buscar rating o fallar
    $rating = Rating::where('product_id', $product_id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
    $rating->update([
        'rating' => $request->rating
    ]);

    return response()->json([
        'message' => 'Valoración actualizada correctamente',
        'data'    => $rating,
        'status'  => 200
    ], 200);

} catch (\Exception $error) {
    return response()->json([
        'error'  => $error->getMessage(),
        'status' => 500
    ], 500);
}
}

    public function destroy(Request $request, Product $product)
    {
    try {
        $user = $request->user();

        // Usamos la relación del usuario
        $deleted = $user->ratings()
                        ->where('product_id', $product->id)
                        ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Valoración eliminada correctamente',
                'status'   => 200
            ], 200);
        }

        return response()->json([
            'message' => 'No se encontró la valoración que desea eliminar',
            'status'  => 404
        ], 404);

    } catch (Exception $error) {
        return response()->json([
            'error'  => 'Error al eliminar puntuación',
            'status' => 500
        ], 500);
    }
}

}