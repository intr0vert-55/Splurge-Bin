@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-body mainbody mt-4">
                    <div class="d-flex flex-row-reverse">
                        <a href="{{route('home')}}" class = "btn btn-outline-secondary">
                            <i class="bi bi-house"></i>
                            Home
                        </a>
                    </div>
                    <div class="container mt-5 mb-5">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-center row">
                            <div class="col-md-10">
                                @if($products -> count != 0)
                                    @foreach($products as $product)
                                        @if($product -> bin != null)
                                        <div class="row p-2 bg-white border rounded items mt-2">
                                            <div class="col-md-3 mt-1">
                                                @php
                                                    $images = explode("->seperator<-", $product ->image);
                                                @endphp
                                                <img class="card-img-top object-fit-cover border rounded" style = "height : 15rem; width : 100%" src="{{asset('/storage/seller_uploads/'.$product -> seller -> id.'/'.$product -> id.'/'.$images[0])}}" alt="{{$product -> name}}">
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <h5>{{$product -> name}}</h5>
                                                <div class="d-flex flex-row">
                                                    <div class="text-warning mb-1 me-2">
                                                        @if($product -> rating != 0)
                                                            @for($i = $product->rating; $i >= 1; $i--)
                                                              <i class="bi bi-star-fill"></i>
                                                            @endfor
                                                            @if(fmod($product->rating, 1) !== 0.00)
                                                                <i class="bi bi-star-half"></i>
                                                            @endif
                                                            @for($i = 5 - $product->rating; $i >= 1; $i--)
                                                                <i class="bi bi-star"></i>
                                                            @endfor
                                                        @endif
                                                      <span class="ms-1 text-primary">
                                                        @if($product->rating != 0)
                                                            {{$product -> rating}} ({{count($product -> reviews)}})
                                                        @else
                                                            No reviews yet
                                                        @endif
                                                      </span>
                                                    </div>
                                                </div>
                                                <div class="mt-1 mb-1 spec-1">{{$product -> info}}</div>
                                                <div class="mt-1 mb-1 spec-1"><h5 class = "sellerName">Sold By : {{$product -> seller -> name}}</h5></div>
                                            </div>
                                            <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                                                <div class="d-flex flex-row align-items-center">
                                                    <h4 class="mr-1 productPrice">{{$product -> priceString}}</h4>
                                                </div>
                                                <div class="d-flex flex-column mt-4">
                                                    <a href = "{{route('products.consumer.view', $product -> id)}}" class="btn btn-primary btn-sm">
                                                        Details
                                                    </a>
                                                    <br>
                                                    <button type = "button" onclick = "removeFromWishList({{$product -> id}})" class = "btn btn-outline-danger">
                                                        Remove from Bin
                                                        <i class="bi bi-trash2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <div class="row p-2 bg-white border rounded items mt-2">
                                        <div class="col-md-3 mt-1">
                                            <img class="card-img-top object-fit-cover border rounded" style = "height : {{$products -> count * 2.5}}rem; width : 100%" src="{{asset('assets/img/logo_small.png')}}" alt="Splurge Bin">
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <h6>Buy Together : </h6>
                                            @foreach($products as $product)
                                                @if($product -> bin != null)
                                                    <div class="mt-1 mb-1 spec-1 productName">{{$product -> name}}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                                            <div class="d-flex flex-row align-items-center">
                                                <h4 class="mr-1 productPrice">{{$products -> price}}</h4>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column mt-4">
                                            <button class = "btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Buy all
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <img src="{{asset('assets/img/no_data.jpg')}}" alt="No data found" style="height : 15rem; width : 50%; object-fit: contain">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Select Address</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('products.bin.buy')}}" onsubmit="checkRadios()" method="post" enctype="multipart/form-data">
                @csrf
                @if(count($details))
                    @foreach($details as $detail)
                        <div class="form-check">
                            <input class="form-check-input detailId" type="radio" name="detailId" value = {{$detail->id}} id="flexRadioDefault1" required>
                            <label class="form-check-label" for="detailId">
                                <span>
                                    {{$detail -> address}}<br>
                                    {{$detail -> state}}<br>
                                    {{$detail -> country}}<br>
                                    {{$detail -> mobile}}<br>
                                </span>
                                {{-- <span>
                                    <button type = "button" onclick = "updateSetter({{$detail}})" class = "btn btn-ad link-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type = "button" class = "btn btn-ad link-dark" onclick = "deleteAddress({{$detail->id}})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </span> --}}
                            </label>
                            <hr>
                        </div>
                    @endforeach
                    @else
                        <h5 class="text-danger">This feature is unavailable unless you add an address</h3>
                        <h5 class="text-warning">Head to your <a href = "{{route('user.profile')}}">profile</a> and add an address there and then try again</h3>
                @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save">Buy</button>
                </div>
            </form>
      </div>
    </div>
  </div>

@endsection

@push('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function removeFromWishList(id){
            $.ajax({
                headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route('product.bin.remove')}}',
                data :{
                    id : id,
                },
                type : 'get',
                success : function(response){
                    swal("Success", response.message, "success").then((isOK) => {
                        window.location.reload();
                    });
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }

        function checkRadios(){
            var len = $('.detailId:checked').length;
            if(len == 0){
                swalAlert();
                // swal("Address not found", "Please select an address", "success");
                // console.log("Error caught");
                return false;
            }
            return true;
        }

        function swalAlert(){
            swal("Address not found", "Please add and/or select an address", "error");
        }


    </script>
@endpush
