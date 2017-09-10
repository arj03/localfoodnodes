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
        <script src="/js/jquery-3.1.1.min.js"></script>

        <!-- CSS -->
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ elixir('css/public.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css">
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

        @yield('modal')

        @include('public.footer')

        <!-- Scripts -->
        <script src="/js/underscore-min.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="https://npmcdn.com/bootstrap@4.0.0-alpha.6/dist/js/bootstrap.min.js"></script>
        <script src="{{ URL::asset('js/jquery.fancybox.min.js') }}"></script>
        <script src="https://unpkg.com/vue"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
        <script>
            $(function() {
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
        <script src="https://embed.small.chat/T0Z3AQJK1G5Q08NBRS.js"></script>
    </body>
</html>
