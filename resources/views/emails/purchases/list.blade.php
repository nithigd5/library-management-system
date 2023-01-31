<x-mail::message>
# Hello Admin,
## These are the Purchases created on {{ $day->toFormattedDateString() }}.

<x-mail::table>
| Purchase ID  | Book | User | Type |
| ---- | :----: | :----: | :----: |
@foreach($purchases as $purchase)
| {{ $purchase->id }} | {{ $purchase->book->name }}| {{ $purchase->user->email }} | {{ $purchase->for_rent ? 'Rent' : 'Owned' }} |
@endforeach
</x-mail::table>

<x-slot:subcopy>
Thanks,<br>
{{ config('app.name') }}
</x-slot:subcopy>
</x-mail::message>
