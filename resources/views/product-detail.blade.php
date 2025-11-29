@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="product-detail-container">
    <div class="container">
        {{-- SECCIÓN PRINCIPAL DEL PRODUCTO --}}
        <div class="product-main">
            <div class="product-layout">
                {{-- IMAGEN DEL PRODUCTO --}}
                <div class="product-image-section">
                    <img src="{{ asset('img/charizar.jpg') }}" 
                         alt="{{ $product->name }}" 
                         class="product-main-image">
                </div>

                {{-- INFORMACIÓN DEL PRODUCTO --}}
                <div class="product-info-section">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <div class="product-price-box">
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    </div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Categoría:</span> 
                            <span class="meta-value">Producto</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Disponibles:</span> 
                            <span class="meta-value">10</span>
                        </div>
                    </div>

                    <div class="product-description">
                        <h3>Descripción:</h3>
                        <p>{{ $product->description ?? 'Este es un producto lanzado en 2022, aproveche el precio.' }}</p>
                    </div>

                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                        <i class="fas fa-shopping-cart"></i>
                        Agregar al carrito
                    </button>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE VALORACIONES --}}
        <div class="ratings-section">
            <div class="container">
                <div class="ratings-content">
                    {{-- RESUMEN DE VALORACIONES --}}
                    <div class="rating-summary">
                        <div class="rating-score">
                            <div class="score-number">{{ number_format($product->average_rating ?? 0, 1) }}</div>
                            <div class="score-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating ?? 0))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= ($product->average_rating ?? 0))
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="total-ratings">{{ $product->ratings_count ?? 0 }} valoraciones</div>
                        </div>
                    </div>

                    {{-- DISTRIBUCIÓN DE ESTRELLAS --}}
                    <div class="rating-distribution">
                        @foreach([5,4,3,2,1] as $star)
                        <div class="distribution-row">
                            <span class="star-label">{{ $star }}</span>
                            <div class="distribution-bar">
                                <div class="bar-bg">
                                    <div class="bar-fill" 
                                         style="width: {{ $product->ratings_count > 0 ? (($ratingDistribution[$star] ?? 0) / $product->ratings_count) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                            <span class="star-count">{{ $ratingDistribution[$star] ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE COMENTARIOS --}}
        <div class="comments-section">
            <div class="comments-header">
                <h2>Valoración y opiniones</h2>
                @auth
                <button class="btn-opinar" onclick="openModal()">Opinar</button>
                @else
                <button class="btn-opinar" onclick="alert('Debes iniciar sesión para opinar')">Opinar</button>
                @endauth
            </div>

            <div class="comments-list">
                @forelse($product->comments as $comment)
                <div class="comment-card">
                    <div class="comment-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=FF1EA8&color=fff&size=70&font-size=0.4&bold=true" 
                             alt="{{ $comment->user->name }}">
                    </div>
                    <div class="comment-content">
                        <h4 class="comment-author">{{ $comment->user->name }}</h4>
                        
                        {{-- Mostrar estrellas si el usuario tiene rating --}}
                        @php
                            $userRating = $comment->user->ratings()->where('product_id', $product->id)->first();
                        @endphp
                        @if($userRating)
                        <div class="comment-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $userRating->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        @endif
                        
                        <p class="comment-text">{{ $comment->content }}</p>
                        <span class="comment-date">{{ $comment->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="no-comments">
                    Aún no hay comentarios. ¡Sé el primero en opinar!
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA OPINAR --}}
@auth
<div class="modal" id="opinionModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h3>Deja tu opinión</h3>
        
        <form action="{{ route('api.comments.store') }}" method="POST" id="opinionForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="form-group">
                <label>Tu calificación:</label>
                <div class="star-rating-input">
                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                    <label for="star{{ $i }}">★</label>
                    @endfor
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Tu comentario:</label>
                <textarea id="comment" 
                          name="content" 
                          rows="4" 
                          placeholder="Comparte tu experiencia con este producto..." 
                          required></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar opinión</button>
        </form>
    </div>
</div>
@endauth

@endsection

@section('scripts')
<script>
    // Abrir modal
    function openModal() {
        document.getElementById('opinionModal').classList.add('show');
    }

    // Cerrar modal
    function closeModal() {
        document.getElementById('opinionModal').classList.remove('show');
    }

    // Cerrar modal al hacer clic fuera
    window.onclick = function(event) {
        const modal = document.getElementById('opinionModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    // Función para agregar al carrito
    function addToCart(productId) {
        alert('Producto agregado al carrito (funcionalidad pendiente)');
    }

    // Enviar formulario de opinión con AJAX
    document.getElementById('opinionForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            product_id: formData.get('product_id'),
            rating: formData.get('rating'),
            content: formData.get('content')
        };

        try {
            const response = await fetch('/api/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('¡Opinión enviada exitosamente!');
                closeModal();
                location.reload();
            } else {
                const error = await response.json();
                alert('Error: ' + (error.message || 'No se pudo enviar la opinión'));
            }
        } catch (error) {
            alert('Error al enviar la opinión: ' + error.message);
        }
    });
</script>
@endsection