@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row">
            <div class="p-5 bg-light">
                <div class="container text-center">
                    <h1 class="display-3">{{ $post->title }}</h1>
                    <h6 class="display-3 text-muted">{{ $post->sub_title }}</h6>
                    <div class="metadata">
                        <div class="category">
                            @if ($post->category)
                                Category: <a class="text-decoration-none text-dark" href="{{ route('categories.posts', $post->category->slug) }}">{{ $post->category->name }}</a>
                            @else
                                <span class="text-muted text-uppercase">Uncategorized</span>
                            @endif
                        </div>
                    </div>

                    <img width="1000px" src="{{ asset('storage/' . $post->cover) }}" alt="">
                    <p class="lead mt-4">{{ $post->body }}</p>
                    
                    <hr class="my-2">
                    <p>More info</p>
                    <div class="tags">
                        Tags:
                        @forelse($post->tags as $tag)
                            <a href="{{route('tags.posts', $tag->slug)}}">{{$tag->name}}</a>
                        @empty
                            <span>Untagged</span>
                        @endforelse

                    </div>
                                <p class="lead">
                        <a class="btn btn-success btn-lg" href="{{ route('posts.index') }}" role="button"><i class="fa fa-backward fa-lg fa-fw" ></i></a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
@endsection