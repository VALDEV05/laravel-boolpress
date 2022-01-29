@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row">
            <div class="p-5 bg-light">
                <div class="container text-center">
                    <h1 class="display-3">{{ $post->title }}</h1>
                    <h6 class="display-3 text-muted">{{ $post->sub_title }}</h6>
                    <img width="1000px" src="{{ $post->cover }}" alt="">
                    <p class="lead mt-4">{{ $post->body }}</p>
                    <p><em>Category: {{ $post->category ? $post->category->name : 'Uncategorized'}}</em></p>
                    <hr class="my-2">
                    <p>More info</p>
                    <p class="lead">
                        <a class="btn btn-success btn-lg" href="{{ route('posts.index') }}" role="button"><i class="fa fa-backward fa-lg fa-fw" ></i></a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
@endsection