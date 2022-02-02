@extends('layouts.admin')
@section('content')
    <div class="p-5 bg-light">
        <div class="container">
            <h3 class="display-5 text-center">Contacts Page</h3>
            <p class="lead text-uppercase text-muted text-center">all of your clients</p>
            <section class="clients">
                <div class="row">
                    @foreach ($contacts as $contact)
                        
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $contact->name }}</h4>
                                    <p class="card-text mb-0">{{ $contact->email }}</p>
                                    <div class="info d-flex justify-content-center align-items-center">
                                        <p class="card-text mb-0">{{ $contact->created_at }}</p>
                                        <div class="badge rounded-pill bg-dark d-flex justify-content-center align-items-center ml-3 text-light" style="width:30px; height:30px">{{ $contact->id }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection