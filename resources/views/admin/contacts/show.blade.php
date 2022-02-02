@extends('layouts.admin')


@section('content')
<div class="p-5 bg-light">
    <div class="container">
        <h1 class="text-center"><i class="fas fa-user-shield fa-fw"></i> VALDEV05 BOOLPRESS <i class="fas fa-user-shield fa-fw"></i></h1>
        <p class="mb-0 text-black-50 text-right"><i class="fas fa-business-time"></i> = {{ $contact->created_at }}</p>
        <hr class="my-2">

        <div id="mail">
            <div id="addresser" class="text-muted mb-4">
                Addresser: <h1 class="text-dark">{{ $contact->email }}</h1>
            </div>
            <div id="name_addresser" class="text-muted mb-4">
                Name: <h4 class="text-dark">{{ $contact->name }}</h4>
            </div>
            <div id="text" class="text-muted">
                Message: <p  class="w-75 text-dark mr-auto">{{ $contact->message}}</p>
            </div>
        </div>





    </div>
</div>
        
@endsection