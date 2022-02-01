@extends('layouts.admin')
@section('content')
    @include('partials.messages')
   <div class="container">
       <section class="new-tags">
           <div id="title">
                <h1 class="text-center my-5">
                    <i class="fas fa-thumbtack fa-lg fa-fw"></i>
                    Add a new Tags
                    <i class="fas fa-thumbtack fa-lg fa-fw"></i>
                </h1>
            </div>
            <form action="{{ route('admin.tags.store') }}" method="post">
                @csrf

                <div class="mb-3 d-flex flex-column align-items-center justify-content-center">
                    <label for="name" class="form-label d-flex justify-content-center">tag</label>
                    <input type="text" name="name" id="name" class="form-control w-75 "  placeholder="Type a tag name her" aria-describedby="nameHelper">
                    <small id="nameHelper" class="text-muted d-flex justify-content-center pt-3">Type a tag name, max 200</small>
                </div>
                <div class="save d-flex justify-content-center">
                    <button type="submit" class="btn btn-outline-primary btn-lg">Add tag</button>
                </div>

            </form>
       </section>
       
        <section class="tags-lists mt-5">
            <h3 class="text-center mb-3"><i class="fas fa-scroll fa-lg fa-fw"></i> Scroll for other tags <i class="fas fa-scroll fa-lg fa-fw"></i></h3> 
            <p class="mb-0 text-center">
                Per modificare una tag clicca sul nome e premi invio
            </p>
            <div class="tags-list">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        @foreach ($tags as $tag)
                            <div class="col-3 d-flex justify-content-center">
                                <div class="card w-100 mt-2">
                                    <div class="card-body">
                                        <h1>{{ $tag->name }}</h1>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
       
   </div>
@endsection