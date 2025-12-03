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
    
    // Mostrar loading
    Swal.fire({
        title: 'Registrando usuario...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ name, email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                html: `
                    <p>Tu cuenta ha sido creada correctamente</p>
                    <p class="text-muted">Serás redirigido al login...</p>
                `,
                confirmButtonColor: '#667eea',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/login';
            });
        } else {
            let errorMessage = 'Error en el registro';
            
            // Mostrar errores de validación
            if (data.errors) {
                const errors = Object.values(data.errors).flat();
                errorMessage = errors.join('<br>');
            } else if (data.message) {
                errorMessage = data.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error en el registro',
                html: errorMessage,
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
