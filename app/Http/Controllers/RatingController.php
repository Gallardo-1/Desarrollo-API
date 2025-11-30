<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Exception;


class RatingController extends Controller
{


    public function index()
{
    try {
        $ratings = Rating::with('user:id,name', 'product:id,name')
            ->latest()
            ->get(['id', 'rating', 'user_id', 'product_id', 'created_at']);

        return response()->json([
            'message' => 'Todas las valoraciones obtenidas exitosamente',
            'data' => $ratings,
            'status' => 200
        ], 200);

    } catch (Exception $error) {
        return response()->json([
            'error' => 'Error al obtener las valoraciones',
            'status' => 500
        ], 500);
    }
}
    /**
     * Almacena o actualiza una valoración para un producto (Requiere Autenticación).
     */
   public function store(Request $request)
{
    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = $request->user();

        // Verificar si ya existe valoración 
        $existingRating = $user->ratings()
                      ->where('product_id', $request->product_id)
                      ->first();  

        if ($existingRating) {
            return response()->json([
                'message' => 'Ya valoraste este producto. Usa UPDATE para modificar tu calificación.',
                'rating_id' => $existingRating->id, 
                'status' => 409
            ], 409);
        }

        $rating = $user->ratings()->create($request->all());

        return response()->json([
            'message' => 'Valoración creada correctamente',
            'data' => $rating,
            'status' => 201
        ], 201);
        
    } catch (\Exception $error) {
        return response()->json([
            'error' => $error->getMessage(),
            'status' => 500
        ], 500);
    }
}
/**
     * Obtiene los PRIMEROS 3 productos mejor rankeados.
     */
    public function topRatedProducts()
    {
        try {
            $topProducts = Product::withAvg('ratings as average_rating', 'rating')
                ->withCount('ratings') 
                ->whereHas('ratings') 
                ->orderByDesc('average_rating')
                ->take(3) 
                ->get();

            if ($topProducts->isEmpty()) {
                return response()->json([
                    'message' => 'No hay productos calificados disponibles para el top 3.',
                    'status' => 404
                ], 404);
            }

            return response()->json([
                'data' => $topProducts,
                'message' => 'Los 3 mejores productos calificados obtenidos exitosamente',
                'status' => 200
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'error' => 'Error al obtener el top 3 de productos calificados: ' . $error->getMessage(),
                'status' => 500
            ], 500);
        }
    }


public function update(Request $request, string $id)  
{
    try {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);
        $rating = Rating::findOrFail($id);

        // Verificar que pertenece al usuario 
        if ($rating->user_id != $request->user()->id) {
            return response()->json([
                'message' => 'No puedes modificar esta valoración',
                'status' => 403
            ], 403);
        }

        $rating->update($request->all());

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

public function destroy( string $id)
{
    try {
    
            $rating = Rating::findOrFail($id);

            $rating->delete();

            return response()->json([
                'message' => 'Rating Deleted Successfully',
                'status' => 200
            ],200);

    } catch (Exception $error) {
        return response()->json([
            'error' => 'Error al eliminar puntuación: ' . $error->getMessage(),
            'status' => 500
        ], 500);
    }
}

public function restore(string $id){
    try {
        $rating = Rating::withTrashed()->findOrFail($id);
        $rating->restore();

        return response()->json([
            'message'=> 'Rating restaurado exitosamente',
            'data' => $rating,
            'status' => 200
        ], 200);

        
    } catch (Exception $error) {
        return response()->json([
            'error' => 'Error al restaurar puntuación: ' . $error->getMessage(),
            'status' => 500
        ], 500);
    }
}

}