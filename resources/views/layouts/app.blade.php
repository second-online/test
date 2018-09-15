<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/all.css') }}" rel="stylesheet"> --}}
    
    <script type="text/javascript">
        window.AppUser = @if(isset($user)) @json($user); @else null; @endif
    </script>
</head>
<body>
    <div id="app"></div>
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>