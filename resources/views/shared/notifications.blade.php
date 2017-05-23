@if (Session::has('message'))
    @foreach (Session::get('message') as $index => $message)
        <div class="alert alert-success master-alert" role="alert" data-dismiss="alert">
            <i class="fa fa-check-circle"></i> {{ $message }}
        </div>
    @endforeach
@endif

@if (Session::has('error'))
    @foreach (Session::get('error') as $index => $error)
        <div class="alert alert-danger master-alert" role="alert" data-dismiss="alert">
            <p>
                <i class="fa fa-warning"></i> {{ $error }}
            </p>
        </div>
    @endforeach
@endif
