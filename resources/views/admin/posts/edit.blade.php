@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="title w-75 justify-content-center ">
            <h1 class="text-center">Edit your post {{ $post->title }}</h1>
        </div>
            <div id="form" class="w-75">
                <form action="{{ route('admin.posts.update', $post->slug) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label d-flex justify-content-center">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Type Here" value="{{ $post->title}}">
                        <small id="title" class="text-muted d-flex justify-content-center pt-1">Tipe here your title | MAX : 200</small>
                    </div>


                    <div class="mb-3">
                        <label for="sub_title" class="form-label d-flex justify-content-center">Sub_title</label>
                        <input type="text" name="sub_title" id="sub_title" class="form-control" placeholder="Type Here" value="{{ $post->sub_title }}">
                        <small id="sub_title" class="text-muted d-flex justify-content-center pt-1">Tipe here your sub_title | MAX : 200</small>
                    </div>

                        
                    <div class="mb-3">
                        <label for="cover" class="form-label d-flex justify-content-center">Cover</label>
                        <input type="text" name="cover" id="cover" class="form-control" placeholder="Type Here" value="{{ $post->cover }}">
                        <small id="cover" class="text-muted d-flex justify-content-center pt-1">Tipe here your cover | MAX : 200</small>
                    </div>


                    <div class="mb-3">
                        <label for="body" class="form-label d-flex justify-content-center">Body</label>
                        <input type="text" name="body" id="body" class="form-control" placeholder="Type Here" value="{{ $post->body }}">
                        <small id="body" class="text-muted d-flex justify-content-center pt-1">Tipe here your body | MAX : 200</small>
                    </div>


                    <div class="form-group">
                        <label for="category_id">Categories</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" {{$category->id == old('category', $post->category_id) ? 'selected' : ''}}>{{$category->name}}</option>
                            @endforeach

                        </select>
                    </div>


                    <div class="actions d-flex justify-content-around align-items-center">
                        <div class="btn-group-mia d-flex flex-column">
                            <label for="body" class="form-label d-flex justify-content-center text-primary">SAVE</label>
                            <button type="submit" class="btn btn-outline-primary rounded-2 btn-lg d-flex justify-content-between align-items text-uppercase"><i class="fas fa-save fa-lg fa-fw"></i></button> 
                        </div>
                        <div class="btn-group-mia d-flex flex-column">
                            <label for="body" class="form-label d-flex justify-content-center text-primary">COME BACK</label>
                            <a class="btn btn-outline-primary rounded-2 btn-lg d-flex justify-content-center" href="{{ route('admin.posts.index') }}"><i class="fas fa-backward fa-lg fa-fw"></i></a>
                        </div>
                    </div>
                </form>
            </div>

    </div>
@endsection