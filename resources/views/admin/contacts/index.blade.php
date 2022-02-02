@extends('layouts.admin')
@section('content')
    <div class="p-5 bg-light">
        <div class="container">
            <h3 class="display-5 text-center">Contacts Page</h3>
            <p class="lead text-uppercase text-muted text-center">all of your clients</p>
            <section class="clients">
                <div class="row justify-content-center">
                    @foreach ($contacts as $contact)
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="card-title mb-0">{{ $contact->name }}</h4>
                                    <p class="card-text mb-0 text-muted">{{ $contact->email }}</p>
                                    <div class="info d-flex justify-content-center align-items-center my-2" style="font-size: 10px">
                                        <p class="card-text mb-0 text-muted">{{ $contact->created_at }}</p>
                                        <div class="badge rounded-pill bg-dark d-flex justify-content-center align-items-center ml-3 text-light" style="width:20px; height:20px">{{ $contact->id }}</div>
                                    </div>
                                    <hr>
                                    <a class="btn btn-outline-primary text-center" href="{{ route('admin.contacts.show', $contact->id) }}" role="button">see all message</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection