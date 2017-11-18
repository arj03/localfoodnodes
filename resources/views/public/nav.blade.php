@if (!Auth::check() || (Auth::user() && Auth::user()->active !== true))

    <nav class="navbar navbar-light navbar-expand-md">
        <a class="navbar-brand" href="/">
            <img src="/images/nav-logo-dark.png">
        </a>

        <button class="navbar-toggler mr-2" type="button" data-toggle="collapse" data-target="#main-navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="main-navbar">
            <ul class="navbar-nav">
                <!-- Visible links on mobile -->
                <li class="nav-item d-xs-block d-md-none">
                    <a class="nav-link" href="/find-out-more">{{ trans('public/nav.find_out_more') }}</a>
                </li>
                <li class="nav-item d-xs-block d-md-none">
                    <a class="nav-link" href="/economy">{{ trans('public/nav.economy') }}</a>
                </li>
                <li class="nav-item d-xs-block d-md-none">
                    <a class="nav-link" href="/membership">{{ trans('public/nav.membership') }}</a>
                </li>
                <li class="nav-item d-xs-block d-md-none">
                    <a class="nav-link" href="/login">{{ trans('public/nav.login') }}</a>
                </li>
                <li class="nav-item d-xs-block d-md-none">
                    <a class="nav-link" href="/account/user/create">{{ trans('public/nav.create') }}</a>
                </li>

                <!-- Fast login hidden on mobile -->
                <li class="dropdown d-none d-md-block">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> {{ trans('public/nav.login_or_create') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right login-dropdown">
                        <li>
                            <form action="/authenticate" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col login-helper">
                                        <a href="/password/reset">{{ trans('public/nav.forgot') }}</a>
                                        <a href="/account/user/create">{{ trans('public/nav.create') }}</a>
                                    </div>
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-success">{{ trans('public/nav.login') }}</button>
                                    </div>
                                </div>
                            </form>
                            <a href="/login/facebook" class="btn btn-success w-100"><i class="fa fa-facebook-square"></i> {{ trans('public/nav.facebook') }}</a></li>
                        </li>
                    </ul>
                </li>

                <!-- Fast login hidden on mobile -->
                <li class="dropdown d-none d-md-block">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ config('app.locales')[App::getLocale()] }}</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach (config('app.locales') as $key => $value)
                            <a class="dropdown-item" href="/settings/locale/{{ $key }}">{{ $value }}</a>
                        @endforeach
                    </div>
                </li>

            </ul>
        </div>
    </nav>
@endif
