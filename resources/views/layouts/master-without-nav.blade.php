<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | Plugando - Gestão de Certificados</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Plataforma de Gestão de Certificados Digitais" name="description" />
        <meta content="Plugando" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
        @livewireStyles
        @include('layouts.head-css')
    </head>

    <body>
        @yield('content')

        @include('layouts.vendor-scripts')
        @livewireScripts
        <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
        @stack('scripts')
    </body>
</html>
