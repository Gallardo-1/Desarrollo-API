<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario de prueba
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Crear usuario admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Crear productos de prueba
        $products = [
            [
                'name' => 'Pokemon - Charizard ex 183/165 - Pokemon 151 - Full Art Ultra Rare',
                'description' => 'Este es un producto lanzado en 2022, aproveche el precio. Carta coleccionable en excelente estado.',
                'price' => 35.00,
            ],
            [
                'name' => 'Poke - mon TCG XY Evolutions Sealed Booster Box',
                'description' => 'Caja sellada con 36 paquetes. Producto oficial de Pokemon TCG.',
                'price' => 73.00,
            ],
            [
                'name' => 'Pokemon - Mew ex 151/165 - Double Rare',
                'description' => 'Carta double rare en perfectas condiciones. Edición limitada.',
                'price' => 45.00,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Crear ratings para cada producto
            Rating::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'rating' => rand(4, 5),
            ]);

            Rating::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'rating' => rand(3, 5),
            ]);

            // Crear comentarios para cada producto
            Comment::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'content' => 'Excelente producto, llegó en perfectas condiciones y muy rápido. Totalmente recomendado.',
            ]);

            Comment::create([
                'product_id' => $product->id,
                'user_id' => $admin->id,
                'content' => 'Producto de muy buena calidad, cumple con lo esperado. El empaque fue muy cuidadoso.',
            ]);
        }
    }
}