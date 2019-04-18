<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/jpg" href="/images/icon.png"/>

    <title>Colorize</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>

    <link href="{{ asset('css/background.css') }}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div id="app">
    <main-app></main-app>
</div>

</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>
