<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ url('assets/img/travel-icon.ico') }}">
    
    <title>@yield('title', 'ViajesInolvidables - Tu Próximo Destino')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ url('assets/css/styles.css') }}">
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-airplane-engines-fill me-2"></i>@yield('navbar', 'VacacionesApp')
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vacacion.index') }}">Destinos</a>
                    </li>
                    
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reserva.index') }}">Mis Reservas</a>
                        </li>
                        
                        {{-- Acceso para Admin o Advanced (pueden gestionar paquetes) --}}
                        @if(Auth::user()->rol == 'admin' || Auth::user()->rol == 'advanced')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Gestionar
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('vacacion.create') }}">Nuevo Paquete</a></li>
                                <li><a class="dropdown-item" href="{{ route('vacacion.lista') }}">Paquetes</a></li>
                                <li><a class="dropdown-item" href="{{ route('tipo.index') }}">Categorías</a></li>
                                <li><a class="dropdown-item" href="{{ route('reserva.reservas') }}">Reservas</a></li>
                                @if(Auth::user()->rol == 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('user.index') }}">Usuarios</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center">
                    <form class="d-none d-md-flex me-3" role="search" method="get" action="{{ route('vacacion.index') }}">
                        @foreach(request()->except(['page','q']) as $item => $value)
                        <input type="hidden" name="{{ $item }}" value="{{ $value }}">
                        @endforeach
                        <input name="q" class="form-control form-control-sm me-2" type="search" value="{{ $q ?? '' }}" placeholder="Buscar destino..." aria-label="Search">
                        <button class="btn btn-outline-light btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    </form>

                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            <li class="nav-item me-2">
                                {{-- Fondo blanco sólido para que resalte mucho --}}
                                <a class="btn btn-light text-primary fw-bold px-3 shadow-sm" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                                </a>
                            </li>
                            <li class="nav-item">
                                {{-- Solo borde blanco: elegante y perfectamente legible sobre azul --}}
                                <a class="btn btn-outline-light fw-bold px-3" href="{{ route('register') }}">
                                    Registrarse
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><span class="dropdown-item-text text-muted small text-uppercase fw-bold">{{ Auth::user()->rol }}</span></li>
                                    
                                    <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        {{-- Enlace que dispara el formulario --}}
                                        <a class="dropdown-item text-danger d-flex align-items-center" href="#" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-power me-2"></i> Cerrar Sesión
                                        </a>

                                        {{-- FORMULARIO NECESARIO (Añade esto) --}}
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        
        @if(session("mensajeTexto"))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session("mensajeTexto") }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Por favor, revisa los errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('modal')
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} VacacionesApp - Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>

</html>