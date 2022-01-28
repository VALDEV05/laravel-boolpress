@extends('layouts.app')


@section('content')
    <div class="contaiener">
        <div class="row">
            <div class="p-5 bg-light">
                <div class="container">
                    <h1 class="display-3">{{ $post->title }}</h1>
                    <h5 class="display-3 text-muted">{{ $post->sub_title }}</h5>
                    <img src="{{ $post->cover }}" alt="">
                    <p class="lead">{{ $post->body }}</p>
                    <hr class="my-2">
                    <p>More info</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="Jumbo action link" role="button">{{ $post->slug }}</a>
                        <a class="btn btn-success btn-lg" href="{{ route('posts.index') }}" role="button">{{ $post->slug }}</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
@endsection