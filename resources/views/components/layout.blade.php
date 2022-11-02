<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InstaClone | @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="icon" type="image/x-icon"
        href="data:image/png;base64,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">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    InstaClone
                </a>
                <search-component></search-component>
                @auth
                <div class="modal fade bd-example-modal-xl" id="activityModal" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="activityModalLabel">Activity</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <activity-component></activity-component>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="flex-grow: 0">
                    @auth
                    <font-awesome-icon icon="fa-regular fa-bell" style="cursor: pointer; font-size: 1.2rem"
                        data-bs-toggle="modal" data-bs-target="#activityModal">
                    </font-awesome-icon>

                    @endauth
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-2">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item ">
                            <a class="nav-link btn btn-primary text-white" href="{{ route('login') }}">{{ __('Log In')
                                }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }}
                            </a>
                            <div class="modal fade bd-example-modal-xl" id="deleteAccount" tabindex="-1"
                                aria-labelledby="deleteAccountLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to permanently delete this account?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <form action="/profile/{{Auth::user()->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    Delete Account
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/profile/{{Auth::user()->id}}">
                                    View Profile
                                </a>
                                <span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteAccount"
                                    style="cursor: pointer">
                                    Delete Account
                                </span>

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            {{$slot}}
        </main>
        {{-- can put footer here --}}
    </div>
</body>

</html>