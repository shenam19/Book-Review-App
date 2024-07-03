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
            @include('layouts.message')


            <div class="card border-0 shadow">
                <div class="card-header  text-white d-flex justify-content-between align-items-center">
                    <div>
                    Reviews

                    </div>


                    <form action=""method="get">
                        <div class="d-flex">
                            <input type="text" name="keyword" id="search" value="{{Request::get('keyword')}}" class="form-control"placeholder="Keyword ">
                            <button type="submit" class="btn btn-info ms-2">Search</button>
                            <a href="{{route('account.reviews')}}"class="btn btn-secondary ms-2">Clear</a>
                        </div>
                    </form>
                </div>
                <div class="card-body pb-0">
                    <table class="table  table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Review</th>
                                <th>Book</th>

                                <th>Rating</th>
                                <th>Create At</th>
                                <th>Status</th>
                                <th width="100">Action</th>
                            </tr>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                @foreach ($reviews as $review)

                                <tr>
                                    <td>{{$review->review}}<br><strong>{{$review->user->name}}</strong></td>
                                    <td>{{$review->book->title}}</td>

                                    <td><i class="fa-regular fa-star"></i> {{$review->rating}}.0</td>
                                    <td>{{\Carbon\Carbon::parse($review->created_at)->format('d M,Y')}}</td>
                                    <td>
                                        @if ($review->status==1)
                                        <span class="text-success">Active</span>

                                        @else
                                        <span class="text-danger">Block</span>

                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('account.reviews.edit',$review->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="#"onclick="deleteReview({{$review->id}})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>


                                @endforeach

                                @endif


                            </tbody>
                        </thead>
                    </table>
                    {{$reviews->links()}}
                </div>

            </div>
        </div>

    </div>
</div>




@endsection
@section('script')
<script>
    function deleteReview(id){
        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url:'{{route('account.reviews.deleteReview')}}',
                data:{id:id},
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'

                },
                success:function(response){
                    window.location.href='{{route('account.reviews')}}'

                }
            })
        }

    }
</script>

@endsection
