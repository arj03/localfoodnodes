<div class="card">
    <div class="card-header">Quantity preview</div>
    <div class="card-block">
        @if ($product->variants()->count() > 0)
            @if ($product->productionType === 'occasional')
                @foreach ($product->productions() as $index => $production)
                    <div class="card">
                        <div class="card-header toggle">
                            {{ $production->date->format('Y-m-d') }}
                            @if ($index === 0)
                                <i class="fa fa-chevron-up"></i>
                            @else
                                <i class="fa fa-chevron-down"></i>
                            @endif
                        </div>
                        <div class="card-block">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Variant</th>
                                        <th>Calculated quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants() as $variant)
                                        <tr>
                                            <td>{{ $variant->name }}</td>
                                            <td>{{ $product->getProductionQuantity($variant, $production->date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Variant</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants() as $variant)
                            <tr>
                                <td>{{ $variant->name }}</td>
                                <td>{{ $product->getProductionQuantity($variant) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>
