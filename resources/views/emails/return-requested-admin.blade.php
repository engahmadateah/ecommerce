<x-mail::message>
# New return request

Order **#{{ $return->order_id }}** — reason: **{{ $return->reason }}**

@if ($return->details)
> {{ $return->details }}
@endif

Open the admin panel (Returns) to approve, reject or refund it.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
