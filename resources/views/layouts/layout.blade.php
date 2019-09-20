<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
      rel="stylesheet"
      href="{{ mix('css/safe-ocean.css') }}"
      type="text/css"
      media="all">
    @yield('styles')
  </head>
  <body>
    @yield('navbar')
    <div id="app">
      @yield('body')
    </div>
    @yield('footer')
    <script charset="utf-8" src="{{ mix('js/manifest.js') }}"></script>
    <script charset="utf-8" src="{{ mix('js/vendor.js') }}"></script>
    @yield('scripts')
  </body>
</html>
