<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Starter Kit</title>
    @include('partials.head-css')
</head>
<body>

    <div class="auth-page-wrapper">
        <div class="auth-card">
            <div class="text-center mb-4">
                <h3>Starter Kit</h3>
                <p class="text-muted">Sign in to continue.</p>
            </div>
            
            @yield('content')
            
        </div>
    </div>
    
    <script src="{{ asset('assets/js/api-client.js') }}"></script>
    @yield('script')
</body>
</html>