<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('layouts.head')
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

        <div id="page-container" class="<?php echo $container_class; ?>">


            @include('layouts.aside')

            @include('layouts.sidebar')

            @include('layouts.header')

            <main id="main-container">

                @yield('content')

            </main>

            @include('layouts.footer')

        </div>

        @include('layouts.foot')

        @yield('footerInclude')
        @include('select2.select2')

    </body>
</html>