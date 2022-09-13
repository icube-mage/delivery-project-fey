<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        [x-cloak] {
            display: none
        }
    </style>
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-100 font-family-karla flex">
        @include('layouts.sidebar')
        <div class="relative w-full flex">
            <div class="w-full min-h-screen overflow-x-hidden border-t flex flex-col">
                @include('layouts.navigation', ['title'=>$title])
                <main class="w-full p-5 flex-grow">
                    <!-- Content goes here! 😁 -->
                    {{ $slot }}
                </main>
                <footer class="w-full bg-white text-right px-4 py-6  border-t-2 border-pink-400">
                    @include('layouts.footer')
                </footer>
            </div>
        </div>

        @livewireScripts
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @vite('resources/js/app.js')
        @stack('scripts')
</body>

</html>
