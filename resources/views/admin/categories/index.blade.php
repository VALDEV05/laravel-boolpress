@extends('layouts.admin')


@section('content')
    <div class="container">
        <h1>asdmaslsdjkaòi</h1>
        <div class="row">
            <div class="col-md-6">
                {{-- form per la creazione di nuove categorie --}}
                <form action="{{route('admin.categories.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                      <label for="name" class="form-label">Category</label>
                      <input type="text" name="name" id="name" class="form-control" placeholder="Type a category name her" aria-describedby="nameHelper">
                      <small id="nameHelper" class="text-muted">Type a category name, max 200</small>
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Add Category</button>
                </form>
            </div>
            <div class="col-md-6">
                {{-- lista categorie  --}}
            </div>
        </div>
    </div>
@endsection