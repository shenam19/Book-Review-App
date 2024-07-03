@extends('layouts.app')
@section('content')


<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-lg">
                @include('layouts.sidebar')
            </div>

        </div>

        <div class="col-md-9">

            <div class="card border-0 shadow">
                <div class="card-header  text-white d-flex justify-content-between align-items-center">
                    <div>
                    Edit Review

                    </div>



                </div>
                <div class="card-body pb-3">

                    <form action="{{route('account.reviews.updateReview',$review->id)}}" method="POST" >
                        @csrf
                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea class="form-control @error('review') is-invalid @enderror" name="review" id="review" cols="30" rows="10" placeholder="Review">{{old('review',$review->review)}}</textarea>
                        @error('review')
                        <div class="invalid-feedback">{{$message}}</div>

                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status"  class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{($review->status==1)?'Selected':""}}>Active</option>
                            <option value="0" {{($review->status==0)?'Selected':""}}>Block</option>

                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{$message}}</div>

                        @enderror
                    </div>

                    <button class="btn btn-primary mt-2">Update</button>
                </form>

                </div>

            </div>
        </div>

    </div>
</div>




@endsection
