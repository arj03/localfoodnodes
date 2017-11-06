<!DOCTYPE html>
<html>
    <head>
        <title>Admin - Local Food Nodes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- CSS -->
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:400" rel="stylesheet">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
    </head>
    <body class="public {{ $viewName }}">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
            <a class="navbar-brand" href="/admin">Admin</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/orders">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/economy">Economy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/api">API</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="admin-app">
            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script src="{{ mix('/js/admin.js') }}"></script>
    </body>
</html>
