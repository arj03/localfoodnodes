@if ($errors->has($field))
    <div class="badge badge-danger">{{ $errors->first($field) }}</div>
@endif
