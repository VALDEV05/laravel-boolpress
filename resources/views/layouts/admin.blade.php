<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div id="app">
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="{{route('guest.home')}}"> <i class="fas fa-user-shield fa-fw"></i> VALDEV05 BOOLPRESS <i class="fas fa-user-shield fa-fw"></i></a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark w-25" type="text" placeholder="Search" aria-label="Search">
            <div class="navbar-nav mr-3">

                <div class="text-white">{{ Auth::user()->name }}</div>

                <div class="logout text-white" aria-labelledby="navbarDropdown">
                    <a class="" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                </li>

            </div>
        </header>

        <div class="container-fluid ">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block  sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('guest.home')}}">
                                    <i class="fa fa-home fa-lg fa-fw"></i>
                                    HomePage
                                </a>
                                <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.dashboard')}}">
                                    <i class="fas fa-tachometer-alt fa-lg fa-fw"></i>
                                    Dashboard
                                </a>
                                <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.posts.index')}}">
                                    <i class="fas fa-blog fa-lg fa-fw"></i>
                                    Blog
                                </a>
                                <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.categories.index')}}">
                                    <i class="fas fa-code-branch fa-lg fa-fw"></i>
                                    Categories
                                </a>
                                <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.tags.index')}}">
                                    <i class="fas fa-thumbtack fa-lg fa-fw" ></i>
                                    Tags
                                </a>
                                 <a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.contacts.index')}}">
                                    <i class="fas fa-phone-alt fa-lg fa-fw" ></i>
                                    Contacts
                                </a>
                            </li>
                        </ul>

                    </div>
                </nav>

                <main class="col-md-9 col-lg-10 px-md-4" style="margin-left: auto;">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>
</html>
