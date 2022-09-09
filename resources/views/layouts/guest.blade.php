<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-slate-200 h-screen">
    {{ $slot }}

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>