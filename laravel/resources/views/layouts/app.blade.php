<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('images/favicon.ico') }}" rel="icon">

    @stack('styles')

    <title>Ambiência - @yield('title')</title>

    @livewireStyles

</head>
<body class="">
@include('layouts.header')
<main class="pb-3">
    @yield('content')
</main>
@include('layouts.footer')
@livewireScripts

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>

</html>
