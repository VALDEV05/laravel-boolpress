@component('mail::message')
# Introduction

{{ $data['message'] }}


@component('mail::panel')
Name:{{ $data['name'] }}

Email:{{ $data['email'] }}
@endcomponent


@component('mail::button', ['url' => $url])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
