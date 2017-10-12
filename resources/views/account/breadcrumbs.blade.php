<div class="breadcrumbs">
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
    @endif
</div>
