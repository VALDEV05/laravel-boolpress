@extends('layouts.admin')


@section('content')
    <div class="container">
        <h1 class="text-center my-5"><i class="fas fa-code-branch fa-lg fa-fw"></i> Add a new category <i class="fas fa-code-branch fa-lg fa-fw"></i></h1>
        <div class="row flex-column">
            <div class="col-12">
                {{-- form per la creazione di nuove categorie --}}
                <form action="{{route('admin.categories.store') }}" method="post">
                    @csrf

                    <div class="mb-3 d-flex flex-column align-items-center justify-content-center">
                      <label for="name" class="form-label d-flex justify-content-center">Category</label>
                      <input type="text" name="name" id="name" class="form-control w-75 "  placeholder="Type a category name her" aria-describedby="nameHelper">
                      <small id="nameHelper" class="text-muted d-flex justify-content-center pt-3">Type a category name, max 200</small>
                    </div>
                    <div class="save d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-primary btn-lg">Add Category</button>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <h3 class="text-center my-5"><i class="fas fa-code-branch fa-lg fa-fw"></i> See all categories <i class="fas fa-code-branch fa-lg fa-fw"></i></h3>
                <ul class="list-group w-25 m-auto">
                    @foreach ($categories as $category)
                        <li class="list-group-item text-center bg-dark text-light text-uppercase">{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection