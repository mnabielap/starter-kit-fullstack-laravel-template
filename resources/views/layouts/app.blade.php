<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Starter Kit</title>
    @include('partials.head-css')
</head>
<body>

    <div class="layout-wrapper">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <div class="main-content">
            <!-- Header -->
            @include('partials.header')

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            
            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/api-client.js') }}"></script>
    @yield('script')
</body>
</html>