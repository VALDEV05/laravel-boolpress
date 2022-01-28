@extends('layouts.admin')
@section('content')
    <div class="container">
        <div id="title" class="my-5">
            <h1 class="text-center text-uppercase">
                <i class="fas fa-newspaper fa-lg fa-fw"></i>
                    Posts
                <i class="fas fa-newspaper fa-lg fa-fw"></i>
            </h1>
        </div>
        <div class="row my-3">
            @foreach ($posts as $post)
                <div class="col-4 mt-3">
                    <div class="card " style="height:200px">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between text-center">
                            <h4 class="card-title w-75">{{ $post->title }}</h4>
                            <p class="card-text">{{ $post->sub_title }}</p>
                            <div class="actions w-100 d-flex justify-content-around">
                                <a href="{{ route('posts.show', $post->slug) }}"><i class="fas fa-eye fa-lg fa-fw"></i></a>
                                <i class="fas fa-pencil-alt fa-lg fa-fw"></i>
                                <i class="fas fa-trash fa-lg fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>   
            @endforeach
            
        </div>
        <div id="paginate" class="mt-5 d-flex justify-content-between">
            <a class="btn btn-outline-warning btn-lg d-flex justify-content-center align-items-center" href="{{ route('admin.posts.create') }}">Create <i class="pl-2 fas fa-user-edit fa-lg fa-fw"></i></a>
            {{ $posts->links() }}
        </div>
    </div>
@endsection