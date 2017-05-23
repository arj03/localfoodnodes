@if ($orderItemDateLink->trashed() || $orderItemDateLink->status === 'cancelled')
    <div class="text-danger">Status: cancelled</div>
@elseif ($orderItemDateLink->status === 'confirmed' || $orderItemDateLink->status === 'delivered')
    <div class="text-success">Status: {{ $orderItemDateLink->status }}</div>
@else
    <div class="text-default">Status: {{ $orderItemDateLink->status }}</div>
@endif
