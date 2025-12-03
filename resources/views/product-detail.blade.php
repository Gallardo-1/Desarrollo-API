@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}?v=1.0">
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
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/500x500?text=Sin+Imagen' }}" 
                         alt="{{ $product->name }}" 
                         class="product-main-image"
                         onerror="this.src='https://via.placeholder.com/500x500?text=Error'">
                </div>

                {{-- INFORMACIÓN DEL PRODUCTO --}}
                <div class="product-info-section">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <div class="product-price-box">
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                    </div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Disponibles:</span> 
                            <span class="meta-value">{{ $product->stock ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="product-description">
                        <h3>Descripción:</h3>
                        <p>{{ $product->description ?? 'Sin descripción disponible.' }}</p>
                    </div>

                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                        <i class="fas fa-shopping-cart"></i>
                        Agregar al carrito
                    </button>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE COMENTARIOS Y VALORACIONES --}}
        <div class="reviews-container">
            {{-- COMENTARIOS - LADO IZQUIERDO --}}
            <div class="comments-section">
                <div class="section-header">
                    <h2><i class="fas fa-comments"></i> Comentarios</h2>
                    <button class="btn-opinar" onclick="checkAuthAndOpenModal()">
                        <i class="fas fa-pen"></i> Escribir Opinión
                    </button>
                </div>

                <div class="comments-list">
                    @forelse($product->comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header-user">
                            <div class="comment-avatar">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=667eea&color=fff&size=50&font-size=0.5&bold=true" 
                                     alt="{{ $comment->user->name }}">
                            </div>
                            <div class="comment-user-info">
                                <h4 class="comment-author">{{ $comment->user->name }}</h4>
                                <span class="comment-date">
                                    <i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Mostrar estrellas si el usuario tiene rating --}}
                        @php
                            $userRating = $comment->user->ratings()->where('product_id', $product->id)->first();
                        @endphp
                        @if($userRating)
                        <div class="comment-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $userRating->rating ? 'filled' : '' }}"></i>
                            @endfor
                        </div>
                        @endif
                        
                        <p class="comment-text">{{ $comment->content }}</p>
                    </div>
                    @empty
                    <div class="no-comments">
                        <i class="far fa-comment-dots"></i>
                        <p>Aún no hay comentarios</p>
                        <span>¡Sé el primero en opinar sobre este producto!</span>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- VALORACIONES - LADO DERECHO --}}
            <div class="ratings-section">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Calificaciones</h2>
                </div>

                <div class="ratings-content">
                    {{-- RESUMEN DE VALORACIONES --}}
                    <div class="rating-summary">
                        <div class="rating-score-container">
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
                            <div class="total-ratings">
                                <i class="fas fa-users"></i> {{ $product->ratings_count ?? 0 }} valoraciones
                            </div>
                        </div>
                    </div>

                    {{-- DISTRIBUCIÓN DE ESTRELLAS --}}
                    <div class="rating-distribution">
                        <h3>Distribución de calificaciones</h3>
                        @foreach([5,4,3,2,1] as $star)
                        <div class="distribution-row">
                            <span class="star-label">
                                {{ $star }} <i class="fas fa-star"></i>
                            </span>
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
    </div>
</div>

{{-- MODAL PARA OPINAR - Siempre presente --}}
<div class="modal" id="opinionModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h3><i class="fas fa-star"></i> Deja tu opinión</h3>
        
        <form id="opinionForm">
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

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Enviar opinión
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Verificar autenticación al cargar la página
    let isUserAuthenticated = false;

    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('auth_token');
        isUserAuthenticated = token ? true : false;
        console.log('Usuario autenticado:', isUserAuthenticated);
        console.log('Token:', token ? 'Presente' : 'Ausente');
        
        // Verificar que el modal existe
        const modal = document.getElementById('opinionModal');
        console.log('Modal encontrado:', modal ? 'Sí' : 'No');
    });

    // Verificar autenticación y abrir modal
    async function checkAuthAndOpenModal() {
        const token = localStorage.getItem('auth_token');
        console.log('Verificando autenticación...');
        console.log('Token encontrado:', token ? 'Sí' : 'No');
        
        if (!token) {
            Swal.fire({
                icon: 'warning',
                title: 'Inicia sesión',
                text: 'Debes iniciar sesión para poder opinar',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Ir al login'
            }).then(() => {
                window.location.href = '/login';
            });
            return;
        }

        // Verificar si el usuario ya comentó en este producto
        try {
            const productId = {{ $product->id }};
            const response = await fetch(`/api/comments/check-user-comment/${productId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.has_commented) {
                Swal.fire({
                    icon: 'info',
                    title: 'Ya opinaste',
                    text: 'Ya has escrito un comentario en este producto. Solo puedes comentar una vez por producto.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
        } catch (error) {
            console.error('Error al verificar comentario:', error);
        }
        
        console.log('Intentando abrir modal...');
        openModal();
    }

    // Abrir modal
    function openModal() {
        const modal = document.getElementById('opinionModal');
        console.log('Modal element:', modal);
        
        if (modal) {
            modal.classList.add('show');
            console.log('Modal abierto - clases:', modal.classList);
        } else {
            console.error('Modal no encontrado en el DOM');
        }
    }

    // Cerrar modal
    function closeModal() {
        const modal = document.getElementById('opinionModal');
        if (modal) {
            modal.classList.remove('show');
            console.log('Modal cerrado');
        }
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
        Swal.fire({
            icon: 'info',
            title: 'Funcionalidad pendiente',
            text: 'Producto agregado al carrito (funcionalidad en desarrollo)',
            confirmButtonColor: '#667eea',
            timer: 2000,
            timerProgressBar: true
        });
    }

    // Enviar formulario de opinión con AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const opinionForm = document.getElementById('opinionForm');
        console.log('Formulario encontrado:', opinionForm ? 'Sí' : 'No');
        
        if (opinionForm) {
            opinionForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('Formulario enviado');
                
                const authToken = localStorage.getItem('auth_token');
                
                if (!authToken) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesión requerida',
                        text: 'Debes iniciar sesión para enviar una opinión',
                        confirmButtonColor: '#667eea'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                    return;
                }
                
                const formData = new FormData(this);
                const data = {
                    product_id: parseInt(formData.get('product_id')),
                    rating: parseInt(formData.get('rating')),
                    content: formData.get('content')
                };

                console.log('Datos a enviar:', data);
                console.log('Token:', authToken.substring(0, 20) + '...');

                // Mostrar loading
                Swal.fire({
                    title: 'Enviando opinión...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch('/api/comments', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${authToken}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });

                    console.log('Status de respuesta:', response.status);
                    const result = await response.json();
                    console.log('Resultado:', result);

                    if (response.ok) {
                        closeModal();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Gracias por tu opinión!',
                            text: 'Tu comentario ha sido publicado exitosamente',
                            confirmButtonColor: '#667eea',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        if (response.status === 401) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sesión expirada',
                                text: 'Tu sesión ha expirado. Por favor inicia sesión nuevamente',
                                confirmButtonColor: '#667eea'
                            }).then(() => {
                                localStorage.removeItem('auth_token');
                                localStorage.removeItem('user_data');
                                window.location.href = '/login';
                            });
                        } else if (response.status === 409) {
                            closeModal();
                            Swal.fire({
                                icon: 'info',
                                title: 'Ya comentaste',
                                text: result.message || 'Ya has escrito un comentario en este producto',
                                confirmButtonColor: '#667eea'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'No se pudo enviar la opinión',
                                confirmButtonColor: '#667eea'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error completo:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor',
                        confirmButtonColor: '#667eea'
                    });
                }
            });
        }
    });
</script>
@endsection