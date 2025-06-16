<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('front-end.layout.links')
</head>
<body>

    @include('front-end.layout.subnav')
    @include('front-end.layout.nav')

    @yield('content')

    @include('front-end.layout.footer')
    @include('front-end.layout.script')

</body>
</html>
