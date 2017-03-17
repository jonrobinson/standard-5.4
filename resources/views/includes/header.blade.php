<header class="container">
    <nav class="nav">
        <div class="nav-left">
            <a class="nav-item is-brand" href="/">
                <h1>Boilerplate</h1>
            </a>
        </div>
        <div class="nav-center">

        </div>
        <div id="nav-menu" class="nav-right nav-menu">
        @if (Auth::check())
            <a class="nav-item" href="{{ url('/') }}">Home</a>
            <span class="nav-item">
                <a class="button" href=" {{ url('/logout') }}">Logout</a>
            </span>
        @else
            <span class="nav-item">
                <a class="button is-primary" href="{{ url('/login') }}">Login</a>
                <a class="button" href="{{ url('/register') }}">Register</a>
            </span>
        @endif
        </div>
    </nav>
</header>
