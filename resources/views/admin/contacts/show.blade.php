@extends('layouts.admin')


@section('content')
@include('partials.messages')
<div class="p-5 bg-light">
    <div class="container">
        <div id="title" class="d-flex justify-content-center">
            <h1 class="text-center mr-auto"><i class="fas fa-user-shield fa-fw"></i> VALDEV05 BOOLPRESS <i class="fas fa-user-shield fa-fw"></i></h1>
            <a class=" btn btn-outline-primary d-flex justify-content-center align-items-center" href="{{ route('admin.contacts.index') }}" role="button"><i class="fa fa-backward fa-lg fa-ffw" aria-hidden="true"></i></a>
        </div>
        <p class="mb-0 mt-3 text-black-50 text-right"><i class="fas fa-business-time"></i> = {{ $contact->created_at }}</p>
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

        <hr class="my-4">
        <form action="{{ route('admin.contacts.store') }}" method="post">
                @csrf

                <div class="mb-4 w-75 m-auto">
                    <div class="row">
                        <div class="col">
                            <label for="name" class="form-label d-flex justify-content-center">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Mario Rossi" aria-describedby="nameId" minlength="4" maxlength="50" required value="Valerio Corda">
                            <small id="NameId" class="text-muted d-flex justify-content-center">Type your name | max:50</small>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label d-flex justify-content-center">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="mariorossi@exaple.com" aria-describedby="emailId" required value="{{ $contact->email }}">
                            <small id="emailId" class="text-muted d-flex justify-content-center">Type your email</small>
                        </div>
                       
                    </div>
                    
                </div>
                <div class="mb-3 w-75 m-auto">
                    <label for="message" class="form-label d-flex justify-content-center">Type your message</label>
                    <textarea class="form-control" name="message" id="message" rows="5" placeholder="Type youre message" required value="{{ old('message') }}"></textarea>
                </div>
                <div class="send d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary w-25 text-uppercase py-3">Send</button>
                </div>
            </form>



    </div>
</div>
        
@endsection