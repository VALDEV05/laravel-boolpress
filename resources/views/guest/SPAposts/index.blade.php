@extends('layouts.app')




@section('content')

    <div class="p-5 bg-light">
        <div class="container text-center">
            <h1 class="display-3"><i class="fab fa-vuejs fa-lg fa-fw"></i> SPA BLOG <i class="fab fa-vuejs fa-lg fa-fw"></i></h1>
            <p class="lead text-muted"><i class="fab fa-forumbee"></i> Qui mostreremo tutta la lista dei post stampati tramite l'utilizzo di un API <i class="fab fa-forumbee"></i></p>
            <hr class="my-2">
            <posts-list></posts-list>
        </div>
    </div>
@endsection