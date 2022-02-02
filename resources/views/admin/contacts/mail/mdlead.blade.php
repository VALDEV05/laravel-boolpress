@component('mail::message')

@component('mail::panel')
Name:{{ $data['name'] }}

Email:{{ $data['email']}}
@endcomponent

{{  $data['message'] }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
