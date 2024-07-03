@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            @include('layouts.message')

            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Profile
                </div>
                <div class="card-body">
                    <form action="{{route('account.updateProfile')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" value="{{old('name',$user->name)}}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" name="name" id="" />
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>

                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Email</label>
                        <input type="text" value="{{old('email',$user->email)}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email"  name="email" id="email"/>
                        @error('email')
                        <div class="invalid-feedback">{{$message}}</div>

                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Image</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                        <div class="invalid-feedback">{{$message}}</div>

                        @enderror
                        @if(Auth::user()->image!="")
                        <img src="{{asset('uploads/profile/thumb/'.Auth::user()->image)}}" class="img-fluid mt-4" alt="Luna John" >
                        @endif



                    </div>
                    <button class="btn btn-primary mt-2">Update</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
