@if ($products->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Produkt</th>
                <th>Producent</th>
                <th>Innehåll</th>
                <th></th>
                <th>Pris</th>
            </tr>
        </thead>
        @foreach ($products->sortBy('name') as $product)
            @if ($product->isVisible($node->id) === true)
                <tr>
                    <td>
                        <a href="{{ $node->permalink()->url }}{{ $product->permalink()->url }}">{{ $product->name }}</a>
                    </td>
                    <td>{{ $product->producer()->name }}</td>
                    <td>
                        @if ($product->variants()->count() > 0)
                            {{ trans('public/node.from') }} {{ $product->smallestVariant()->getPriceWithUnit() }}
                        @else
                            {{ $product->getPriceWithUnit() }}
                        @endif
                    </td>
                </div>
            @endif
        @endforeach
    </table>
@else
    {{ trans('public/node.no_products') }}
@endif
