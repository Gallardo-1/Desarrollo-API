<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->string('image_url')->nullable()->after('price'); //url de la imagen del producto
            $table->integer('stock')->default(0)->after('image_url'); //cantidad en inventario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('image_url', 'stock');
        });
    }
};
