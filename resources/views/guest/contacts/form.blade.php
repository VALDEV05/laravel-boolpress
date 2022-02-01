@extends('layouts.app')





@section('content')
    @include('partials.messages')
    @include('partials.errors')
    <div class="p-5 bg-light">
        <div class="container">
            <h2 class="text-center display-3"><i class="fa fa-phone-alt fa-lg fa-fw"></i> Contacts <i class="fa fa-phone-alt fa-lg fa-fw"></i></h2>
            <p class="text-center text-uppercase text-muted lead">we will help you if you need</p>
        </div>
        <div class="container">
            <form action="{{ route('guest.contacts.send') }} " method="post">
                @csrf

                <div class="mb-4 w-75 m-auto">
                    <div class="row">
                        <div class="col">
                            <label for="name" class="form-label d-flex justify-content-center">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Mario Rossi" aria-describedby="nameId" minlength="4" maxlength="50" required value="{{ old('name') }}">
                            <small id="NameId" class="text-muted d-flex justify-content-center">Type your name | max:50</small>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label d-flex justify-content-center">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="mariorossi@exaple.com" aria-describedby="emailId" required value="{{ old('email') }}">
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