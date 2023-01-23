@props(['purchases'])

<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <th>Book</th>
            <th>User</th>
            <th>Payment</th>
            <th>Mode</th>
            <th>Type</th>
            <th>Purchased at</th>
            <th>Action</th>
        </tr>
        @foreach($purchases as $purchase)
            <tr>
                <td><a href="#">{{ $purchase->book->name }}</a></td>
                <td class="font-weight-600">{{ $purchase->user->first_name.' '.$purchase->user->last_name }}</td>
                <td>
                    <div
                        class="badge badge-{{ $getPaymentStatusBadge($purchase->payment_status) }}">{{ $purchase->payment_status }}</div>
                </td>
                <td>{{ $purchase->mode }}</td>
                <td>{{ $purchase->for_rent ? 'Rent' : 'Owned'  }}</td>
                <td>{{ $purchase->created_at->toDayDateTimeString() }}</td>
                <td>
                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-primary">Detail</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $purchases->links() }}
</div>
