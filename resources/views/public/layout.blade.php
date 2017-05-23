<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title') - Local Food Nodes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ URL::asset('css/public.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/jquery.fancybox.min.css') }}">
        <script src="/js/jquery-3.1.1.min.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="https://npmcdn.com/bootstrap@4.0.0-alpha.6/dist/js/bootstrap.min.js"></script>
        <script src="{{ URL::asset('js/jquery.fancybox.min.js') }}"></script>

        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
    </head>
    <body class="public {{ $viewName }} {{ Auth::check() ? 'logged-in' : '' }}">
        <div class="page">
            @include('admin.user-nav')
            @include('public.nav')
            @include('admin.user-nav-mobile')
            <div class="content">
                <div class="container-fluid">
                    @include('shared.errors')
                    @yield('content')
                </div>
            </div>
        </div>

        @include('public.footer')

        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
        <script>
            jQuery(document).ready(function() {
                $('.slick-slider').slick({
                    arrows: false,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 1000,
                });
            });
        </script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-90169652-1', 'auto');
            ga('send', 'pageview');
        </script>
        @yield('modal')
    </body>
</html>
