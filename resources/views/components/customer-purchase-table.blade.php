@props(['purchases'])

<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <th>Book</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Mode</th>
            <th>Type</th>
            <th>Purchased at</th>
            <th>Action</th>
        </tr>
        @foreach($purchases as $purchase)
            <tr>
                <td><a href="{{ route('book.show', $purchase->book->id) }}">{{ $purchase->book->name }}</a></td>
                <td>
                    <div
                        class="badge badge-{{ $getPurchaseStatusBadge($purchase) }}">{{ $purchase->getPurchaseStatus() }}</div>
                </td>
                <td>
                    <div
                        class="badge badge-{{ $getPaymentStatusBadge($purchase) }}">{{ $purchase->getPaymentStatus() }}</div>
                </td>
                <td>{{ $purchase->mode }}</td>
                <td>{{ $purchase->for_rent ? 'Rent' : 'Owned'  }}</td>
                <td>{{ $purchase->created_at->toDateString() }}</td>
                <td>
                    <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-primary">Detail</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $purchases->links() }}
</div>
