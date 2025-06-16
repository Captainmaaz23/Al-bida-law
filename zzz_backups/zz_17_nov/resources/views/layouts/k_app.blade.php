<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('layouts.head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('headerInclude')
    </head>
    <body>
        <?php
        $container_class = "sidebar-o enable-page-overlay side-scroll page-header-fixed main-content-narrow";
        if (Auth::user()) {
            $header = Auth::user()->header;
            $sidebar = Auth::user()->sidebar;

            if ($header == 'dark' && $sidebar == 'dark')
                $container_class .= " page-header-dark sidebar-dark";
            elseif ($header == 'dark')
                $container_class .= " page-header-dark";
            elseif ($sidebar == 'dark')
                $container_class .= " sidebar-dark";
        }
        ?>

        @yield('content')

        @include('layouts.foot')

        @yield('footerInclude')

    </body>
</html>