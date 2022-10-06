<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{asset('assets/images/fairytail.png')}}">
    <title>{{$title}}</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-slate-200 h-screen">
    {{ $slot }}

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>