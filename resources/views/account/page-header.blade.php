<div class="row page-header d-none d-lg-flex">
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
        @endif
    </div>
</div>
