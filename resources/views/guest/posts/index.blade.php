@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row">
            @foreach ($posts as $post)
               <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card mt-4" style="height: 350px">
                        <img class="card-img-top" src="{{ $post->cover }}" alt="">
                        <div class="card-body d-flex flex-column justify-content-between text-center">
                            <h2 class="card-title">{{ $post->title}}</h2>
                            <p class="card-text">{{ $post->sub_title }}</p>
                            <a class="btn btn-outline-primary btn-lg mb-1" href="route{{ 'posts.show' }}">View More </a>
                        </div>

                    </div>
                </div> 
            @endforeach
            
        </div>
    </div>
@endsection