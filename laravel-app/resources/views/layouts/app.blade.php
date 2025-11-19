<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') - My Laravel Site</title>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; margin: 0; padding: 0; color: #222; }
        header { background:#0b72b9; color:#fff; padding:1rem; }
        nav a { color:#fff; margin-right:1rem; text-decoration:none; }
        main { padding:1rem; max-width:900px; margin:0 auto; }
        footer { padding:1rem; text-align:center; color:#666; font-size:.9rem; }
        .card { background:#fff; padding:1rem; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,.08); }
        .flash { margin-bottom:.75rem; padding:.5rem .75rem; background:#eef; border:1px solid #cde; border-radius:4px; }
        .alert { margin-bottom:.75rem; padding:.5rem .75rem; border-radius:4px; }
        .alert-success { background:#d4edda; border:1px solid #c3e6cb; color:#155724; }
        .alert-danger { background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; }
        input, textarea { width:100%; padding:.5rem; margin:.4rem 0 1rem 0; border:1px solid #ddd; border-radius:4px; box-sizing:border-box; }
        button { background:#0b72b9; color:#fff; border:none; padding:.6rem 1rem; border-radius:4px; cursor:pointer; }
        .error { color:#721c24; font-size:0.875rem; margin-top:-0.5rem; margin-bottom:1rem; }
    </style>
</head>
<body>
<header>
    <div style="max-width:900px;margin:0 auto;">
        <h1 style="margin:0;font-size:1.25rem;">My Laravel Site</h1>
        <nav>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('info') }}">Info</a>
        </nav>
    </div>
</header>

<main>
    @if(session('flash'))
        <div class="alert alert-success">{{ session('flash') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="card" style="display:inline-block;">
        &copy; {{ date('Y') }} My Laravel Site
    </div>
</footer>
</body>
</html>