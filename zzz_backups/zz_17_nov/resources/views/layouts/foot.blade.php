<script src="{{ asset_url('/js/oneui.app.js') }}"></script>
<?php /* ?><script src="{{ url('/public/js/laravel.app.js') }}"></script>-->



<!-- Laravel Scaffolding JS -->
  <!-- <script src="{{mix('/js/laravel.app.js') }}"></script> --><?php */ ?>

@yield('js_after')
@stack('scripts')