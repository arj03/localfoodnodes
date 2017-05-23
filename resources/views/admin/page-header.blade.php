<div class="row page-header hidden-md-down">
    <div class="col-12 d-flex justify-content-between">
        @if (isset($breadcrumbs))
            <div class="breadcrumbs">
                @foreach ($breadcrumbs as $label => $url)
                    @if ($url)
                        <span class="crumb"><a href="/account/{{ $url }}">{{ $label }}</a></span>
                    @else
                        <span class="crumb">{{ $label }}</span>
                    @endif
                @endforeach
            </div>
        @elseif (isset($title))
            <b>{{ $title }}</b>
        @endif
    </div>
</div>
