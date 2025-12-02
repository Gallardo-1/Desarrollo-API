@extends('layouts.app')

@section('title', 'Inicio')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
    <section class="hero-section">
        <div class="banner-container">
            <img src="{{ asset('img/banner.webp') }}" alt="Banner Pokemon" class="banner-image">
            <div class="banner-overlay">
                <a href="#all-products" class="btn-banner">
                    Ver Productos
                </a>
            </div>
        </div>
    </section>

    @if(isset($products) && $products->count() > 0)
    {{-- Productos Destacados (últimos 3 productos) --}}
    <section class="products-section">
        <div class="container">
            <h2 class="section-title"><span>D</span>estacados</h2>
            
            <div class="products-grid">
                @foreach($products->take(3) as $product)
                <div class="product-card">
                    <a href="{{ route('product.detail', $product->id) }}" class="product-image-link">
                        <div class="product-image">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300x300?text=Sin+Imagen' }}" 
                                 alt="{{ $product->name }}"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=Error'">
                        </div>
                    </a>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                        <div class="product-rating">
                            @php
                                $avgRating = $product->ratings->avg('rating') ?? 0;
                                $fullStars = floor($avgRating);
                                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                            @endphp
                            
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star"></i>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="rating-count">({{ number_format($avgRating, 1) }})</span>
                        </div>
                        <button class="btn-primary" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Todos los Productos --}}
    <section class="products-section" id="all-products">
        <div class="container">
            <h2 class="section-title"><span>T</span>odos los <span>P</span>roductos</h2>
            
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('product.detail', $product->id) }}" class="product-image-link">
                        <div class="product-image">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300x300?text=Sin+Imagen' }}" 
                                 alt="{{ $product->name }}"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=Error'">
                        </div>
                    </a>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                        <div class="product-rating">
                            @php
                                $avgRating = $product->ratings->avg('rating') ?? 0;
                                $fullStars = floor($avgRating);
                                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                            @endphp
                            
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star"></i>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="rating-count">({{ number_format($avgRating, 1) }})</span>
                        </div>
                        <button class="btn-primary" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="products-section">
        <div class="container">
            <div style="text-align: center; padding: 4rem 2rem;">
                <i class="fas fa-box-open" style="font-size: 5rem; color: #ddd; margin-bottom: 1rem;"></i>
                <h2 style="color: #666;">No hay productos disponibles</h2>
                <p style="color: #999; margin-top: 1rem;">Vuelve pronto para ver nuevos productos</p>
            </div>
        </div>
    </section>
    @endif
@endsection

@section('scripts')
<script>
    function addToCart(productId) {
        alert('Producto ' + productId + ' agregado al carrito (funcionalidad pendiente)');
    }
</script>
@endsection