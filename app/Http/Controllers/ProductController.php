<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cloudinary\Cloudinary;

class ProductController extends Controller
{
    private $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret')
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::orderBy('id', 'desc')->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imageUrl = null;

            // Si hay una imagen en base64, subirla a Cloudinary
            if ($request->has('image') && $request->image) {
                $uploadedImage = $this->cloudinary->uploadApi()->upload($request->image, [
                    'folder' => 'products',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ]);
                $imageUrl = $uploadedImage['secure_url'];
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock ?? 0,
                'image' => $imageUrl
            ]);

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'image' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $imageUrl = $product->image;

            // Si hay una nueva imagen en base64, subirla a Cloudinary
            if ($request->has('image') && $request->image && strpos($request->image, 'data:image') === 0) {
                // Eliminar imagen anterior de Cloudinary si existe
                if ($product->image) {
                    $this->deleteCloudinaryImage($product->image);
                }

                $uploadedImage = $this->cloudinary->uploadApi()->upload($request->image, [
                    'folder' => 'products',
                    'transformation' => [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit'
                    ]
                ]);
                $imageUrl = $uploadedImage['secure_url'];
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock ?? $product->stock,
                'image' => $imageUrl
            ]);

            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            // Eliminar imagen de Cloudinary si existe
            if ($product->image) {
                $this->deleteCloudinaryImage($product->image);
            }

            $product->delete();

            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function deleteCloudinaryImage($imageUrl)
    {
        try {
            // Extraer public_id de la URL de Cloudinary
            preg_match('/\/v\d+\/(.+)\.\w+$/', $imageUrl, $matches);
            if (isset($matches[1])) {
                $publicId = $matches[1];
                $this->cloudinary->uploadApi()->destroy($publicId);
            }
        } catch (\Exception $e) {
            // Log error pero no detener la operación
            \Log::error('Error al eliminar imagen de Cloudinary: ' . $e->getMessage());
        }
    }

    public function indexWeb()
    {
        try {
            // Obtener todos los productos con sus relaciones
            $products = Product::with(['ratings', 'comments'])->orderBy('id', 'desc')->get();
            
            // Calcular el promedio de rating para cada producto
            $products->each(function ($product) {
                $product->average_rating = $product->ratings->avg('rating') ?? 0;
            });
            
            // Obtener los 3 productos mejor valorados
            $topRatedProducts = $products->sortByDesc('average_rating')->take(3);
            
            return view('home', compact('products', 'topRatedProducts'));
        } catch (\Exception $e) {
            return view('home', ['products' => collect(), 'topRatedProducts' => collect()]);
        }
    }

    public function showWeb($id)
    {
        try {
            $product = Product::with(['comments.user', 'ratings'])->findOrFail($id);
            
            // Calcular promedio de rating
            $product->average_rating = $product->ratings->avg('rating') ?? 0;
            $product->ratings_count = $product->ratings->count();
            
            // Distribución de ratings
            $ratingDistribution = [
                5 => $product->ratings->where('rating', 5)->count(),
                4 => $product->ratings->where('rating', 4)->count(),
                3 => $product->ratings->where('rating', 3)->count(),
                2 => $product->ratings->where('rating', 2)->count(),
                1 => $product->ratings->where('rating', 1)->count(),
            ];
            
            return view('product-detail', compact('product', 'ratingDistribution'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Producto no encontrado');
        }
    }
}

