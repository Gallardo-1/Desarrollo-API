@extends('layouts.app')

@section('title', 'Inicio')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<section class="hero-section">
    <div class="banner-container">
        <img src="{{ asset('img/banner.webp') }}" alt="Banner" class="banner-image">
        <div class="banner-overlay">
            <a href="#products" class="btn btn-primary btn-banner">Ver</a>
        </div>
    </div>
</section>

<section class="products-section" id="products">
    <div class="container">
        <h2 class="section-title"><span>P</span>roductos <span>D</span>estacados</h2>
        <div class="products-grid">
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
                    <button class="btn btn-primary">Agregar al Carrito</button>
                </div>
            </div>
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
                    <button class="btn btn-primary">Agregar al Carrito</button>
                </div>
            </div>
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
                    <button class="btn btn-primary">Agregar al Carrito</button>
                </div>
            </div>
           
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Verificar si el usuario está autenticado
window.onload = function() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        window.location.href = '/';
        return;
    }
};
</script>
@endsection
