<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi Tienda')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="{{ asset('css/navbarnavbar.css') }}?v={{ rand(1000, 9999) }}">
     <link rel="stylesheet" href="{{ asset('css/footer.css') }}?v={{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>
    <!-- Primer Navbar - Superior -->
    <nav class="navbar-top">
        <div class="container">
            <div class="navbar-top-content">
                <!-- Logo -->
                <div class="navbar-logo">
                    <img src="{{ asset('img/nuevologo.png') }}" alt="Logo">
                </div>

                <!-- Buscador -->
                <div class="navbar-search">
                    <input type="text" placeholder="Buscar productos..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- Carrito y Usuario -->
                <div class="navbar-actions">
                    <a href="#" class="action-icon cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge">0</span>
                    </a>
                    <div class="user-menu">
                        <button class="action-icon user-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dropdown-menu">
                            <span class="user-info" id="userInfo">Cargando...</span>
                            <hr>
                            <a href="#" onclick="logout()">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Segundo Navbar - Menú de Navegación -->
    <nav class="navbar-bottom">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="/home" class="nav-link">Inicio</a></li>
                <li><a href="/products" class="nav-link">Productos</a></li>
                <li><a href="/cards" class="nav-link">Cartas</a></li>
                <li><a href="/collectibles" class="nav-link">Coleccionables</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h3>Sobre Nosotros</h3>
                    <p>Tu tienda online sin derechos reservados hecha como proyecto de desarrollo por un equipo
                        con ganas de aprender y crecer en el mundo del desarrollo web usando una API creada 
                        por nosotros mismos.
                    </p>
                </div>

                <div class="footer-section team">
                    <h3>Nuestro Equipo</h3>
                    <div class="team-grid">
                        <div class="team-member">
                            <img src="https://avatars.githubusercontent.com/u/87998271?v=4" alt="Miembro 1">
                            <h4>Juan Carlos</h4>
                            <a href="https://github.com/Dragnel6" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="team-member">
                            <img src="https://avatars.githubusercontent.com/u/165742781?v=4" alt="Miembro 2">
                            <h4>Kenia Lizbeth</h4>
                            <a href="https://github.com/keniadev" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="team-member">
                            <img src="https://avatars.githubusercontent.com/u/150270429?v=4" alt="Miembro 3">
                            <h4>Luis Hernández</h4>
                            <a href="https://github.com/Luis-Hdez" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="team-member">
                            <img src="https://avatars.githubusercontent.com/u/156029632?v=4" alt="Miembro 4">
                            <h4>HenryGeovanniGT</h4>
                            <a href="https://github.com/Gallardo-1" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="team-member">
                            <img src="https://avatars.githubusercontent.com/u/218527944?v=4" alt="Miembro 5">
                            <h4>Jonatan Ely</h4>
                            <a href="https://github.com/Jonatandev01" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>

                        <div class="team-member">
                            <img src="{{ asset('img/team/member6.jpg') }}" alt="Miembro 6">
                            <h4>Nombre Miembro 6</h4>
                            <a href="https://github.com/usuario6" target="_blank" class="github-link">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // Función de logout
        async function logout() {
            const authToken = localStorage.getItem('auth_token');
            
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                });
                
                if (response.ok) {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/';
                }
            } catch (error) {
                localStorage.removeItem('auth_token');
                window.location.href = '/';
            }
        }

        // Toggle dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            const userBtn = document.querySelector('.user-btn');
            const dropdown = document.querySelector('.dropdown-menu');
            
            if (userBtn && dropdown) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                });

                // Cerrar dropdown al hacer click fuera
                window.addEventListener('click', function(e) {
                    if (!e.target.matches('.user-btn') && !e.target.closest('.user-menu')) {
                        if (dropdown.classList.contains('show')) {
                            dropdown.classList.remove('show');
                        }
                    }
                });
            }

            // Cargar info del usuario
            const authToken = localStorage.getItem('auth_token');
            const userInfo = document.getElementById('userInfo');
            if (authToken && userInfo) {
                userInfo.textContent = 'Usuario Logueado';
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
