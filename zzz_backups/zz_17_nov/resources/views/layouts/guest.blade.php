<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head> 
        @include('layouts.head')
        @yield('headerInclude')
    </head>
    <body>
        <div id="page-container">

            <main id="main-container">
                @yield('content')
            </main>

        </div>

        <script src="{{ asset_url('js/oneui.app.js') }}"></script>

        @yield('js_after')
    </body>
</html>