<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

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
            $comments = Comment::with(['user', 'product'])->get();

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
        //
        //
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'content' => 'required|string',
            ]);

            // create coment con modelos relacionados
            $comment = $request->user()->comments()->create($request->all());

            return response()->json([
                'data' => $comment,
                'message' => 'Comment created successfully',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            //
            return response()->json([
                'message' => 'Error creating comment: ' . $e->getMessage(),
                'status' => 500
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
    public function update(string $id, Request $request)
    {
        //
        try {
            // buscamos el comentario por id
            $comment = Comment::findOrFail($id);

            //actualizamos el comentario
            $comment->update($request->all());

            return response()->json([
                'data' => $comment,
                'message' => 'Comment updated successfully',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            //
            return response()->json([
                'message' => 'Error updating comment:' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // 
        try {
            // buscamos el comentario por id
            $comment = Comment::findOrFail($id);

            // eliminamos el comentario
            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            //
            return response()->json([
                'message' => 'Error deleting comment: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
