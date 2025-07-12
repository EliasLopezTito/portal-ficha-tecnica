<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal Cielo')</title>

    <meta name="description" content="@yield('description', 'Portal Cielo - Tu mejor opción en bienes raíces en Perú')">
    <meta name="keywords" content="@yield('keywords', 'inmobiliaria, bienes raíces, Perú, Portal Cielo, compra, venta, alquiler')">
    <meta name="author" content="Portal Cielo">
    <meta name="robots" content="index, follow">

    <link rel="shortcut icon" type="image/svg" href="{{ asset('assets/images/logos/isotipo_black.svg') }}">

    @php
        $vite = ['resources/css/technicalSheet.scss'];
        array_push($vite, 'resources/js/technicalSheet.js');
    @endphp

    @vite($vite)

</head>

<body>

    {{-- Loader --}}
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/isotipo_blanco.svg') }}" alt="Logo Portal Cielo">
    </div>
    {{-- End Loader --}}

    @yield('content', 'Sin contenido')

</body>

</html>
