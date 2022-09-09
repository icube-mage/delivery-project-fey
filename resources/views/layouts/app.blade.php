<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        [x-cloak] { display: none }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="bg-gray-100 font-family-karla flex">
        <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        @include('layouts.navigation')
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <!-- Content goes here! 😁 -->
                {{ $slot }}
            </main>
            <footer class="w-full bg-white text-right p-4 border-t">
                version {{ env('APP_VERSION')}}
            </footer>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.8.1/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/js/app.js')
    @livewireScripts
    @stack('scripts')
</body>
</html>