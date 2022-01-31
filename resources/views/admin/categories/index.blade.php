@extends('layouts.admin')


@section('content')
    @if (session('message'))
        <div class="alert alert-warning text-center">
            {{ session('message') }}
        </div>
    @endif
    <div class="container">
        <h1 class="text-center my-5"><i class="fas fa-code-branch fa-lg fa-fw"></i> Add a new category <i class="fas fa-code-branch fa-lg fa-fw"></i></h1>
        <div class="row mb-5">
            <div class="col-12 ">
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
            <div class="col-12 mt-5">
                <h3 class="text-center mb-3"><i class="fas fa-scroll fa-lg fa-fw"></i> Scroll for other categories <i class="fas fa-scroll fa-lg fa-fw"></i></h3> 
                <div class="categories-list">
                    <div class="container">
                        <div class="row d-flex justify-content-center">
                            @foreach ($categories as $category)
                                <div class="col-3 d-flex justify-content-center">
                                    <div class="card w-100 mt-2">
                                        <div class="card-body">
                                            <form action="{{ route('admin.categories.update', $category->id) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <input type="text" name="name" id="name" class="form-control border-0 text-center "  value="{{ $category->name }}" aria-describedby="nameHelper">


                                                </form>
                                            <div class="actions d-flex justify-conten-center">
                                                @if ($category->posts()->count() > 0)
                                                    <a  class="mr-auto text-decoration-none text-dark"  href="{{ route('categories.posts', $category->slug) }}">
                                                        <div class="badge rounded-pill bg-success d-flex justify-content-center align-items-center " style="width:30px; height:30px">
                                                            {{ $category->posts()->count() }}
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="badge rounded-pill bg-warning d-flex justify-content-center align-items-center mr-auto" style="width:30px; height:30px">{{ $category->posts()->count() }}</div>
                                                @endif
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" ></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection