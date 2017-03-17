<!DOCTYPE html>
<html>
    <head>
        <link rel=icon href="img/favicon-96x96.png" sizes="96x96" type="image/png">
        <link rel='shortcut icon' href='images/favicon.png' type='image/x-icon'/>
        <meta id="_token" value="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <title>Boilerplate</title>
    </head>
    <body>
        <div id="app" class="body">
            @include('includes.header')
            <div class="main content">
                @yield('content')
            </div>
            @include('includes.footer')
        </div>
        <script src='{{ asset("js/app.js") }} '></script>
    </body>
</html>
