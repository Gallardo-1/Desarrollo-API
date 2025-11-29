@extends('layouts.app')

@section('title', 'Inicio')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
    <section class="hero-section">
        <div class="banner-container">
            <img src="{{ asset('img/banner.webp') }}" alt="Banner Pokemon" class="banner-image">
            <div class="banner-overlay">
                <a href="{{ route('products') }}" class="btn-banner">
                    Ver Productos
                </a>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Destacados</h2>
            
            <div class="products-grid">
                {{-- Producto 1 --}}
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('img/charizar.jpg') }}" alt="Charizard">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Pokemon - Charizard ex 183/165 - Pokemon 151 - Full Art Ultra Rare</h3>
                        <p class="product-price">$35.00</p>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="rating-count">(4.5)</span>
                        </div>
                        <button class="btn-primary" onclick="addToCart(1)">
                            <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                        </button>
                        <a href="{{ route('product.detail', ['product' => 1]) }}" class="btn-view-detail">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </div>
                </div>

                {{-- Producto 2 --}}
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('img/charizar.jpg') }}" alt="Charizard">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Pokemon - Charizard ex 183/165 - Pokemon 151 - Full Art Ultra Rare</h3>
                        <p class="product-price">$35.00</p>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="rating-count">(4.5)</span>
                        </div>
                        <button class="btn-primary" onclick="addToCart(2)">
                            <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                        </button>
                        <a href="{{ route('product.detail', ['product' => 2]) }}" class="btn-view-detail">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </div>
                </div>

                {{-- Producto 3 --}}
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('img/charizar.jpg') }}" alt="Charizard">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Pokemon - Charizard ex 183/165 - Pokemon 151 - Full Art Ultra Rare</h3>
                        <p class="product-price">$35.00</p>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="rating-count">(4.5)</span>
                        </div>
                        <button class="btn-primary" onclick="addToCart(3)">
                            <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                        </button>
                        <a href="{{ route('product.detail', ['product' => 3]) }}" class="btn-view-detail">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function addToCart(productId) {
        alert('Producto ' + productId + ' agregado al carrito (funcionalidad pendiente)');
    }
</script>
@endsection