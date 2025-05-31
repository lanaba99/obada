<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Royal Crown') }}</title> {/* Uses your app name from .env */}

    {{-- These are Vite's special Blade directives. --}}
    {{-- @viteReactRefresh enables Fast Refresh for React during development. --}}
    @viteReactRefresh
    {{-- @vite includes the compiled CSS and JavaScript from Vite. --}}
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="font-sans antialiased">
    {{-- This is the crucial div. React will attach itself here. --}}
    <div id="app">
        {{-- You could put a "Loading..." message or a spinner here that shows before React loads. --}}
    </div>
</body>
</html>