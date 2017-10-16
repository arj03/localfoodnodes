@if (isset($breadcrumbs))
    <div class="breadcrumbs-wrapper container-fluid">
        <div class="container">
            <div class="breadcrumbs">
                @foreach ($breadcrumbs as $label => $url)
                    @if ($url)
                        <span class="crumb"><a href="/account/{{ $url }}">{{ $label }}</a></span>
                    @else
                        <span class="crumb">{{ $label }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
