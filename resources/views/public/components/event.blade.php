<div class="event">
    <a href="{{ $event->permalink()->url }}" class="row no-gutters">
        <div class="col-3 date">
            <div class="day">{{ $event->start_datetime->format('d') }}</div>
            <div class="month">{{ $event->start_datetime->format('F') }}</div>
        </div>
        <div class="col-9 info">
            <div class="block">
                <h2 class="bold">{{ $event->name }}</h2>
                <div>
                    <b>{{ trans('public/index.where') }}</b>{{ $event->address }} {{ $event->city }}
                </div>
                <div>
                    <b>{{ trans('public/index.when') }}</b>
                    {{ $event->start_datetime->format('Y-m-d H:i') }} - {{ $event->end_datetime->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
    </a>
</div>
