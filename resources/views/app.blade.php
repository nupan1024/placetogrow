<!DOCTYPE html>
<html xml:lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <title>{{ config('app.name') }}</title>
        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-200">
        @inertia
    </body>
</html>
