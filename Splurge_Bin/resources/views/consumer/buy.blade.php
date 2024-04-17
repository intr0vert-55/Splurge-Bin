@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                        <h2 class = "productName">{{$product -> name}}</h2>
                </div>
                <div class="card-body mainbody mt-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif
                        <div class="container">
                            <div class="row gx-5">
                              <aside class="col-lg-6">
                                <div class="border rounded-4 mb-3 d-flex justify-content-center">
                                  @php
                                    $images = $product -> image;
                                    $images = explode("->seperator<-", $images);
                                    $path = '/storage/seller_uploads/'.$product->seller_id.'/'.$product->id.'/';
                                  @endphp
                                  <a data-fslightbox="mygalley" class="rounded-4" id = "coverImg" target="_blank" data-type="image" href="#">
                                    <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="{{asset($path.$images[0])}}"" />
                                  </a>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallImages" id = "cvrImg0" target="_blank" data-type="image" href="#">
                                      <img width="60" height="60" class="rounded-2" style = "border:3px solid #087990" src="{{asset($path.$images[0])}}"/>
                                    </a>
                                @for($i = 1; $i < count($images) - 1; $i++)
                                    <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallImages" id = "cvrImg{{$i}}" target="_blank" data-type="image" href="#">
                                      <img width="60" height="60" class="rounded-2" src="{{asset($path.$images[$i])}}"/>
                                    </a>
                                @endfor
                                </div>
                                <!-- thumbs-wrap.// -->
                                <!-- gallery-wrap .end// -->
                              </aside>
                              <main class="col-lg-6 bg-white">
                                <div class="ps-lg-3">
                                  <div class="d-flex flex-row my-3">
                                    <div class="text-warning mb-1 me-2">
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fas fa-star-half-alt"></i>
                                      <span class="ms-1">
                                        RATING SHOULD BE HERE
                                      </span>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <h4 class = "productPrice">
                                        {{$product -> priceString}}
                                    </h4>
                                    <s style = "color:red">
                                        {{$product -> fakePrice}}
                                    </s>
                                  </div>

                                  <h5 class = "sellerName">Sold By : {{$product -> seller -> name}}</h5>
                                  <hr>

                                  <form action="{{route('product.purchase', $product->id)}}" onsubmit="return checkRadios()" method="post">
                                        @csrf
                                  <div class="form-group buttons">
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
                                                    <span>
                                                        <button type = "button" onclick = "updateSetter({{$detail}})" class = "btn btn-ad link-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type = "button" class = "btn btn-ad link-dark" onclick = "deleteAddress({{$detail->id}})">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </span>
                                                </label>
                                                <hr>
                                            </div>
                                        @endforeach
                                    @endif
                                        <a class = "btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick = "addSetter()">
                                            Add an address
                                            <i class="bi bi-plus-circle"></i>
                                        </a>
                                        <div class="d-flex flex-row-reverse bd-highlight">
                                            <button class = "btn btn-primary btn-buy buyProduct" type="submit">
                                                Buy
                                                <i class="bi bi-cart2"></i>
                                            </button>
                                            <a href="{{route('products.consumer.view', $product->id)}}" class = "btn btn-outline-secondary btn-buy">
                                                <i class="bi bi-x-lg"></i>
                                                Cancel
                                            </a>
                                        </div>
                                    </form>
                                  </div>
                        </main>
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add address</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Mobile</label>
                    <input type="number" name = "number" class="form-control mobile" id="exampleFormControlInput1" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                    <textarea class="form-control address" name = "address" id="exampleFormControlTextarea1" rows="3" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" name = "state" class="form-control state" id="state" required>
                  </div>
                  <div class="mb-3">
                    <label for="state" class="form-label">Country</label>
                    <input type="text" name = "country" class="form-control country" id="country" required>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save">Save changes</button>
                </div>
            </form>
      </div>
    </div>
  </div>
@endsection

@push('script')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

        $(document).ready(function(){

            // Script for image toggle

            $('.smallImages').on('click', function(e){
                e.preventDefault();
                var len = $('.smallImages').length;
                var reqId = "#cvrImg";
                while(--len > -1){
                    // Removing all borders
                    $(reqId + len + " img").css("border", "none");
                }
                var img = "#" + $(this).attr('id') + " img";
                // Setting the border
                $(img).css("border", "3px solid #087990")
                img = $(img).attr('src');
                // Changing the source of the cover image
                $('#coverImg img').attr('src', img);

            });

            // Script for rollable cover image
            // Code written by ChatGPT

            $('#coverImg img').on('mousemove', function(e) {
                const rect = e.target.getBoundingClientRect();
                const x = e.clientX - rect.left; // X-coordinate within the image
                const y = e.clientY - rect.top; // Y-coordinate within the image
                const zoomAmount = 1.5; // Set the zoom amount

                const offsetX = x / rect.width * 100;
                const offsetY = y / rect.height * 100;

                $(this).css({
                  'transform-origin': offsetX + '% ' + offsetY + '%', // Set transform origin
                  'transform': 'scale(' + zoomAmount + ')' // Apply zoom
                });
            });

            $('#coverImg img').on('mouseleave', function() {
                $(this).css('transform', 'scale(1)'); // Reset zoom on mouse leave
            });


        });

        // Script for checking if a radio tag is checked


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

        function addSetter(){
            $('.modal-title').text("Add address");
            $('.mobile').val('');
            $('.address').val('');
            $('.state').val('');
            $('.country').val('');
            $('.save').html('Add');
            $('.save').attr("onclick", "addAddress()");
        }

        function updateSetter(detail){
            // console.log(detail);
            $('.modal-title').text("Update address");
            $('.mobile').val(detail.mobile);
            $('.address').val(detail.address);
            $('.state').val(detail.state);
            $('.country').val(detail.country);
            $('.save').html('Update');
            $('.save').attr("onclick", "updateAddress("+ detail.id +")");
        }

        

        function addAddress(){
            var mobile = $('.mobile').val();
            var address = $('.address').val();
            var state = $('.state').val();
            var country = $('.country').val();
            var token = $('[name="_token"]').val();
            console.log(token);
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method : 'post',
                url : '{{route('address.add')}}',
                data : {
                    mobile : mobile,
                    address : address,
                    state : state,
                    country : country,
                    _token : token,
                },
                success : function(){
                    swal("Done", "Address added successfully", "success").then((isOK) => {
                        window.location.reload();
                    });
                },
                error : function(jqXHR, response){
                    swal("Something went wrong", "Address is not added", "error");
                }
            });
        }

        function updateAddress(id){
            var mobile = $('.mobile').val();
            var address = $('.address').val();
            var state = $('.state').val();
            var country = $('.country').val();
            var token = $('[name="_token"]').val();
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method : 'post',
                url : '{{route('address.update')}}',
                data : {
                    id : id,
                    mobile : mobile,
                    address : address,
                    state : state,
                    country : country,
                    _token : token,
                },
                success : function(){
                    swal("Done", "Address updated successfully", "success").then((isOK) => {
                        window.location.reload();
                    });
                },
                error : function(jqXHR, response){
                    swal("Something went wrong", "Address is not updated", "error");
                }
            });
        }

        function deleteAddress(id){
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method : 'delete',
                url : '{{route('address.delete')}}',
                data : {
                    id : id,
                },
                success : function(){
                    swal("Done", "Address deleted successfully", "success").then((isOK) => {
                        window.location.reload();
                    });
                },
                error : function(jqXHR, response){
                    swal("Something went wrong", "Address is not deleted", "error");
                }
            });
        }

    </script>
@endpush
