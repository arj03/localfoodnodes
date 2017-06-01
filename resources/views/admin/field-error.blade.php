@if ($errors->has($field))
    @foreach ($errors->get($field) as $error)
        <div class="badge badge-danger">
            {{ $error }}
        </div>
    @endforeach
@endif
