<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            // obtenemos todos los comentarios  con sus relaciones
            $comments = Comment::with(['user', 'product'])->latest()->get();

            return response()->json([
                'data' => $comments,
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            // 
            return response()->json([
                'message' => 'Error fetching comments: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
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
    public function store(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return response()->json([
                'message' => 'No autenticado'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creando comentario', [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id
            ]);

            // Crear el comentario
            $comment = Comment::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'content' => $request->content
            ]);

            // Crear o actualizar el rating
            $rating = Rating::updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'user_id' => auth()->id()
                ],
                [
                    'rating' => $request->rating
                ]
            );

            Log::info('Comentario creado exitosamente', [
                'comment_id' => $comment->id,
                'rating_id' => $rating->id
            ]);

            return response()->json([
                'message' => 'Comentario y valoración guardados exitosamente',
                'comment' => $comment,
                'rating' => $rating
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al guardar comentario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al guardar el comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $coment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $coment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $comment = Comment::findOrFail($id);

            // Verificar que el usuario sea el dueño del comentario
            if ($comment->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'No autorizado'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment->update([
                'content' => $request->content
            ]);

            return response()->json([
                'message' => 'Comentario actualizado exitosamente',
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // Verificar que el usuario sea el dueño del comentario
            if ($comment->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'No autorizado'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'message' => 'Comentario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
