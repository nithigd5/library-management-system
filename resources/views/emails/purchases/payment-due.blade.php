<x-mail::message>
# Hello {{ $purchase->user->first_name }}!
**********************************************************


Payment has been due on **{{ $purchase->payment_due->toFormattedDateString() }}**

**********************************************************

<br/>

<h2 style="text-align: center"> Purchase Details </h2>

**Book Bought On:** {{ $purchase->book_issued_at->toFormattedDateString() }}

**Pending Amount:** @money($purchase->price)

**********************************************************

<h2 style="text-align: center"> Book Details </h2>

**Book Price:** @money($purchase->pending_amount)


**Book Name:** {{ $purchase->book->name }}

**Book Author:** {{ $purchase->book->author }}

**********************************************************

<x-mail::button :url="route('purchase.show', $purchase->id)">
View Purchase Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
