@extends('layouts.guest')

@section('title', 'Registro')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="floating-shapes"></div>
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Crear Cuenta</h1>
            <img src="{{ asset('img/nuevologo.png') }}" alt="Logo">
        </div>

        <form id="registerForm" class="auth-form">
            <div class="form-group">
                <input type="text" id="name" name="name" class="form-control" placeholder=" " required>
                <label for="name">Nombre de Usuario</label>
            </div>

            <div class="form-group">
                <input type="email" id="email" name="email" class="form-control" placeholder=" " required>
                <label for="email">Correo Electrónico</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder=" " required minlength="8">
                <label for="password">Contraseña</label>
            </div>

            <button type="submit" class="auth-submit">
                Registrarse
            </button>
        </form>

        <div class="auth-links">
            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ name, email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showAlert('¡Registro exitoso! Puedes iniciar sesión ahora.');
            setTimeout(() => window.location.href = '/', 2000);
        } else {
            showAlert(data.message || 'Error en el registro', 'error');
        }
    } catch (error) {
        showAlert('Error de conexión', 'error');
    }
});
</script>
@endsection
