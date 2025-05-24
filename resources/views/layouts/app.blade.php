<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikacioni për Kërkimin e Ilaçeve</title>
    @livewireStyles
    <style>
        html, body { margin: 0; padding: 0; height: 100%; font-family: Arial, sans-serif; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .main-content { flex: 1 0 auto; max-width: 900px; margin: 0 auto; }
        footer { flex-shrink: 0; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<nav style="padding: 10px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <a href="{{ url('/') }}" style="color: #000000; font-size: 25px; font-weight: bold; text-decoration: none; margin-bottom: 10px;">TENTON</a>
        @auth
            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('drug-search') }}" style="color: #ffffff; margin-right: 15px; text-decoration: none; margin-bottom: 10px;">Aplikacioni për Kërkimin e Ilaçeve</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background-color: #3b82f6; color: #ffffff; padding: 12px 25px; border-radius: 5px; border: none;  cursor: pointer; font-size: 14px;">Logout</button>
                </form>
            </div>
        @else
            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('register') }}" style="color: #000000; padding: 6px 12px; border-radius: 3px; text-decoration: none; margin-bottom: 10px; font-size: 14px;">Register</a>
                <a href="{{ route('login') }}" style="background-color: #3b82f6; color: #ffffff; padding: 12px 25px; border-radius: 5px; text-decoration: none; margin-right: 10px; margin-bottom: 10px; font-size: 14px;">Login</a>
            </div>
        @endauth
    </div>
</nav>

<div class="main-content" style="padding: 20px 0;">
    {{ $slot }}
</div>

<footer style="padding: 15px 20px; color: #ffffff; text-align: center;">
    <div style="max-width: 900px; margin: 0 auto;">
        <p>
            <a href="http://www.tenton.co"  target="_blank" style="color: #000000; text-decoration: underline; margin-right: 100px; transition: color 0.3s;" onmouseover="this.style.color='#d1d5db'" onmouseout="this.style.color='#000000'">www.tenton.co</a> |
            <a href="mailto:jobs@tenton.co" style="color: #000000; text-decoration: underline; margin-left: 100px; transition: color 0.3s;" onmouseover="this.style.color='#d1d5db'" onmouseout="this.style.color='#000000'">jobs@tenton.co</a>
        </p>
    </div>
</footer>

@livewireScripts
</body>
</html>
