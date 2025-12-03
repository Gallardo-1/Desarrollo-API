@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="auth-container">
    <div class="floating-shapes"></div>
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('img/nuevologo.png') }}" alt="Logo">
            <h1 class="auth-title">Bienvenid@</h1>
        </div>

        <form id="loginForm" class="auth-form">
            <div class="form-group">
                <input type="email" id="email" name="email" class="form-control" placeholder=" " required>
                <label for="email">Correo Electrónico</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder=" " required>
                <label for="password">Contraseña</label>
            </div>

            <button type="submit" class="auth-submit">
                Iniciar Sesión
            </button>
        </form>

        <div class="auth-links">
            <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Mostrar loading
    Swal.fire({
        title: 'Iniciando sesión...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            localStorage.setItem('auth_token', data.access_token);
            localStorage.setItem('user_data', JSON.stringify(data.user));
            
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: 'Inicio de sesión exitoso',
                confirmButtonColor: '#667eea',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                if (data.user && data.user.is_admin === true) {
                    window.location.href = '/admin/products';
                } else {
                    window.location.href = '/home';
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Credenciales incorrectas',
                confirmButtonColor: '#667eea'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#667eea'
        });
    }
});
</script>
@endsection
