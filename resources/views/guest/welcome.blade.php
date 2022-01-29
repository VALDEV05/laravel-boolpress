@extends('layouts.app')


@section('content')
    <h1 class="text-center">Home Page welcome visitors</h1>


    <div class="row justify-content-center">
      <div class="col-4">
        <div class="card text-center">
          <div class="card-body">
            <h3 class="card-title"><i class="fa fa-newspaper fa-lg fa-fw"></i> BLOG <i class="fa fa-newspaper fa-lg fa-fw"></i></h3>
           <button type="submit" class="btn btn-outline-primary btn-lg text-light"> <a class="text-uppercase" href="{{ route('posts.index') }}">view Blog</a></button>
          </div>
        </div>
      </div>
    </div>
@endsection