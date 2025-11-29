@extends('layouts.app')

@section('title', 'Coleccionables')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<section class="products-section">
    <div class="container">
        <h2 class="section-title"><span>A</span>rtículos <span>C</span>oleccionables</h2>
        <div class="products-grid">
            @for($i = 1; $i <= 6; $i++)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ asset('img/Coleccin.jpg') }}" alt="Coleccionable {{ $i }}">
                </div>
                <div class="product-info">
                    <h3 class="product-name">Pokemon TCG: Colección de figuras premium de celebraciones - Pikachu VMAX</h3>
                    <p class="product-price">${{ rand(25, 150) }}.00</p>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <span class="rating-count">(4.0)</span>
                    </div>
                    <button class="btn-primary" onclick="addToCart({{ $i }})">
                        <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                    </button>
                    <a href="{{ route('product.detail', ['product' => $i]) }}" class="btn-view-detail">
                        <i class="fas fa-eye"></i> Ver Detalle
                    </a>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function addToCart(productId) {
        alert('Coleccionable ' + productId + ' agregado al carrito (funcionalidad pendiente)');
    }
</script>
@endsection