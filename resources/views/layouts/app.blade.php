<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    @vite('resources/css/app.css')
    <style>
        [x-cloak] { display: none }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased">
    {{ $slot }}

    <script defer src="https://unpkg.com/alpinejs@3.8.1/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
    @livewireScripts
    @stack('scripts')
</body>
</html>