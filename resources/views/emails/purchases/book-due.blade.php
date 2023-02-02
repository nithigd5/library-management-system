<x-mail::message>
# Hello {{ $purchase->user->first_name }}!

**********************************************************


Book has been due on **{{ $purchase->book_return_due->toFormattedDateString() }}**

**********************************************************

<br>

<h2 style="text-align: center"> Book Details </h2>

**Book Name:** {{ $purchase->book->name }}

**Book Author:** {{ $purchase->book->author }}

**Book Bought On:** {{ $purchase->book_issued_at->toFormattedDateString() }}

********************************************************************************

<x-mail::button :url="route('purchase.show', $purchase->id)">
View Purchase Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
