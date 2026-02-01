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
    <style>
        .navbar-brand .logo-container {
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand:hover .logo-container {
            transform: scale(1.1) rotate(15deg);
            background-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
        }

        /* Animación de rotación lenta para el globo */
        .bi-globe-americas {
            animation: rotateGlobe 10s linear infinite;
        }

        @keyframes rotateGlobe {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Evitar que rote cuando pasamos el ratón para que se lea la brújula */
        .navbar-brand:hover .bi-globe-americas {
            animation-play-state: paused;
        }

        .fw-black {
            font-weight: 900;
        }
    </style>
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo-container position-relative bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" 
                    style="width: 45px; height: 45px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                    <i class="bi bi-globe-americas fs-4 text-white"></i>
                    {{-- Pequeño detalle de brújula absoluta --}}
                    <i class="bi bi-compass position-absolute bottom-0 end-0 bg-white text-primary rounded-circle shadow-sm" 
                    style="font-size: 0.75rem; padding: 2px; transform: translate(20%, 10%);"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <span class="fw-black lh-1 text-white" style="letter-spacing: 1px; font-size: 1.4rem; font-family: 'Arial Black', sans-serif;">Vacacion</span>
                    <span class="fw-light lh-1 text-white-50 text-uppercase" style="font-size: 0.75rem; letter-spacing: 4px;">App</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vacacion.index') }}">Destinos</a>
                    </li>
                    
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reserva.index') }}">Mis Reservas</a>
                        </li>
                        
                        @if(Auth::user()->rol == 'admin' || Auth::user()->rol == 'advanced')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill me-1"></i> Gestionar
                            </a>
                            <ul class="dropdown-menu shadow border-0 mt-2">
                                <li><a class="dropdown-item" href="{{ route('vacacion.create') }}"><i class="bi bi-plus-circle me-2"></i>Nuevo Paquete</a></li>
                                <li><a class="dropdown-item" href="{{ route('vacacion.lista') }}"><i class="bi bi-list-ul me-2"></i>Paquetes</a></li>
                                <li><a class="dropdown-item" href="{{ route('tipo.index') }}"><i class="bi bi-tags me-2"></i>Categorías</a></li>
                                @if(Auth::user()->rol == 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('reserva.reservas') }}"><i class="bi bi-calendar-check me-2"></i>Reservas</a></li>
                                    <li><a class="dropdown-item text-primary" href="{{ route('user.index') }}"><i class="bi bi-people me-2"></i>Usuarios</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center ms-auto">
                    <form class="d-none d-md-flex me-3" role="search" method="get" action="{{ route('vacacion.index') }}">
                        @foreach(request()->except(['page','q']) as $item => $value)
                            <input type="hidden" name="{{ $item }}" value="{{ $value }}">
                        @endforeach
                        <div class="input-group input-group-sm">
                            <input name="q" class="form-control border-white border-opacity-25 bg-white bg-opacity-10 text-white" type="search" value="{{ $q ?? '' }}" placeholder="Buscar destino...">
                            <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>

                    <ul class="navbar-nav align-items-center">
                        @guest
                            <li class="nav-item me-2">
                                <a class="btn btn-light text-primary fw-bold px-3 shadow-sm btn-sm" href="{{ route('login') }}">
                                    Entrar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-light fw-bold px-3 btn-sm" href="{{ route('register') }}">
                                    Registrarse
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle fs-5 me-2"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li><span class="dropdown-item-text text-muted small text-uppercase fw-bold">{{ Auth::user()->rol }}</span></li>
                                    <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger d-flex align-items-center" href="#" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-power me-2"></i> Cerrar Sesión
                                        </a>
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
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session("mensajeTexto") }}
                <button type="button" class="btn-close" data-bs-alert="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>¡Atención!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('modal')
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1 fw-bold">VACACIONES App</p>
            <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} - Tu Próxima Aventura comienza aquí.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>