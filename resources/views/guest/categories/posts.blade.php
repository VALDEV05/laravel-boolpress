@extends('layouts.app')


@section('content')
    <div class="p-5 bg-light">
        <div class="container">
            <header class="d-flex justify-content-center">
                <div id="title">
                    <h1 class="text-center">Category: <span class="fw-bolder">{{ $category->name }}</span></h1>
                    <p class="lead text-center">All posts of this category</p>
                </div>
                <div class="close-view ml-auto pt-4">
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('posts.index') }}"><i class="fa fa-backward"></i></a>
                </div>
            </header>
            
            <div class="row justify-content-center">
                @forelse ($posts as $post)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card mt-4" style="height: 350px">
                            <img class="card-img-top" src="{{ $post->cover }}" alt="">
                            <div class="card-body d-flex flex-column justify-content-between text-center">
                                <h4 class="card-title">{{ $post->title}}</h4>
                                <p class="card-text">{{ $post->sub_title }}</p>
                                <a class="btn btn-outline-primary btn-lg mb-1" href="{{ route('posts.show', $post->slug) }}">View More </a>
                            </div>
                        </div>
                    </div> 
                    {{-- /.col --}}
                @empty
                <div class="col">
                    <p>Vuoto</p>
                </div>
                @endforelse
            </div>
            {{-- /.row --}}
            <div class="paginate d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
    </div>
@endsection