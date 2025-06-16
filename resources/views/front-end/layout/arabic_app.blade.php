<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('front-end.layout.links')
</head>
<body>

    @include('front-end.Layout_Arabic.subnav')
    @include('front-end.Layout_Arabic.nav')

    @yield('content')

    @include('front-end.Layout_Arabic.footer')
    @include('front-end.layout.script')

</body>
</html>
