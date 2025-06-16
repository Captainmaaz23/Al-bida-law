<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>{{ env('APP_NAME', 'Sufra') }}</title>
        <meta name="description" content="{{ env('APP_NAME', 'Sufra') }}">
        <meta name="author" content="{{ env('APP_NAME', 'Sufra') }}">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset_url('media/favicons/favicon.png') }}">
        <link rel="icon" sizes="192x192" type="image/png" href="{{ asset_url('media/favicons/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset_url('media/favicons/apple-touch-icon-180x180.png') }}">

        @yield('css_before')

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" id="css-main" href="{{ asset_url('css/oneui.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset_url('css/custom.css') }}">

        @yield('css_after')