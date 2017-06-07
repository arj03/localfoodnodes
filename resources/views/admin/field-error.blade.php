@if ($errors->has($field))
    @foreach ($errors->get($field) as $error)
        <div>
            <div class="badge badge-danger">
                {{ $error }}
            </div>
        </div>
    @endforeach
@endif
