<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title') - Local Food Nodes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Facebook meta -->
        <meta property="fb:app_id" content="923888444420982" />
        <meta property="og:url" content="{{ $fbUrl or app('url')->to('/') }}">
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="{{ $fbTitle or 'Local Food Nodes' }}">
        <meta property="og:description" content="{!! $fbDescription or 'We are creating local food nodes in order to connect local food producers to local food consumers as well as strengthening those relationships that already exist. We want to enable direct transactions, resilient communities and regain control over what we eat and how it is produced. A desire to make food local again.' !!}" />
        <meta property="og:image" content="{{ $fbImage or URL::asset('images/facebook-share.jpg') }}">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

        <!-- CSS -->
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('css/public.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/slick.css') }}"/>
    </head>
    <body class="public {{ $viewName }} {{ Auth::check() && Auth::user()->active ? 'logged-in' : '' }}">
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.9&appId=923888444420982";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <div class="page">
            @include('account.user-nav')
            @include('public.nav')
            @include('account.user-nav-mobile')
            <div class="content">
                <div class="container-fluid">
                    @include('shared.errors')
                    @yield('content')
                </div>
            </div>
        </div>

        @yield('modal')

        @include('public.footer')

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="/js/underscore-min.js"></script>
        <script src="{{ URL::asset('js/jquery.fancybox.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/slick.min.js') }}"></script>
        <script>
            $(function() {
                $('.slick-slider-wrapper').slick({
                    arrows: false,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 1000,
                });
            });
        </script>
        <script src="https://d3js.org/d3.v4.min.js"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-90169652-1', 'auto');
            ga('send', 'pageview');
        </script>
        <script src="https://embed.small.chat/T0Z3AQJK1G5Q08NBRS.js"></script>
    </body>
</html>
