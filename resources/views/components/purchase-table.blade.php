@props(['purchases'])

<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <th>Book</th>
            <th>User</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Book Mode</th>
            <th>Type</th>
            <th>Purchased at</th>
            <th>Action</th>
        </tr>
        @foreach($purchases as $purchase)
            <tr>
                <td><a href="{{ route('admin.books.show', $purchase->book->id) }}">{{ $purchase->book->name }}</a></td>
                <td class="font-weight-600"><a
                        href="{{ route('admin.customers.show', $purchase->user->id) }}">{{ $purchase->user->first_name.' '.$purchase->user->last_name }}</a>
                </td>
                <td>
                    <div
                        class="badge badge-{{ $getPurchaseStatusBadge($purchase) }}">{{ $purchase->getPurchaseStatus() }}</div>
                </td>
                <td>
                    <div
                        class="badge badge-{{ $getPaymentStatusBadge($purchase) }}">{{ $purchase->getPaymentStatus() }}</div>
                </td>
                <td>{{ $purchase->book->mode }}</td>
                <td>{{ $purchase->for_rent ? 'Rent' : 'Owned'  }}</td>
                <td>{{ $purchase->created_at->toDateString() }}</td>
                <td>
                    <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="btn btn-primary">Detail</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $purchases->links() }}
</div>
