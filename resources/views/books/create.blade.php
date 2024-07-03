@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
            @include('layouts.sidebar')

            </div>

            <div class="col-md-9">
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Add Book
                    </div>
                    <div class="card-body">
                        <form action="{{route('books.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control  @error('title') is-invalid   @enderror" placeholder="Title" name="title" id="title" value="{{old('title')}}" />
                            @error('title')
                            <div class="invalid-feedback">{{$message}}</div>

                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control  @error('title') is-invalid   @enderror" placeholder="Author"  name="author" id="author" value="{{old('author')}}"/>
                            @error('author')
                            <div class="invalid-feedback">{{$message}}</div>

                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control " placeholder="Description" cols="30" rows="5" >{{old('title')}}</textarea>
                            {{-- @error('description')
                            <div class="invalid-feedback">{{$message}}</div>

                            @enderror --}}
                        </div>

                        <div class="mb-3">
                            <label for="Image" class="form-label">Image</label>
                            <input type="file" class="form-control  "  name="image" id="image"/>
                            @error('image')
                            <div class="invalid-feedback">{{$message}}</div>

                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control  ">
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                            </select>

                        </div>


                        <button class="btn btn-primary mt-2">Create</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
