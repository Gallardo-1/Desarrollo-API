@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
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
    
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            localStorage.setItem('auth_token', data.access_token);
            showAlert('¡Inicio de sesión exitoso!');
            setTimeout(() => window.location.href = '/home', 1500);
        } else {
            showAlert(data.message || 'Error al iniciar sesión', 'error');
        }
    } catch (error) {
        showAlert('Error de conexión', 'error');
    }
});
</script>
@endsection
