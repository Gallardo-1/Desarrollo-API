@extends('layouts.app')

@section('title', 'Productos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<section class="products-section">
    <div class="container">
        <h2 class="section-title"><span>T</span>odos los <span>P</span>roductos</h2>
        <div class="products-grid">
            @for($i = 1; $i <= 3; $i++)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ asset('img/busteer.webp') }}" alt="Producto {{ $i }}">
                </div>
                <div class="product-info">
                    <h3 class="product-name">Poke - mon TCG XY Evolutions Sealed Booster Box, 36 paquetes por caja</h3>
                    <p class="product-price">${{ rand(20, 100) }}.00</p>
                    <div class="product-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="rating-count">(4.5)</span>
                    </div>
                    <button class="btn btn-primary">Agregar al Carrito</button>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
window.onload = function() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/';
        return;
    }
};
</script>
@endsection
