{{--

MunkiReport Interia Layout

This layout exists to test the InertiaJS stack.
Depending on whether this is successful it may be renamed.

--}}<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>{{ config('app.name', 'MunkiReport') }} ALPHA</title>
    <!-- Styles -->
   {{-- @if (config('frontend.css.use_cdn', false)) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    {{--    @endif--}}

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="mr-inertia-layout">
    @inertia
</body>
</html>
