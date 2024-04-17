@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                        <h3>{{$product -> name}}</h3>
                        <a href="{{ url()->previous() }}" class = "btn">
                            <i class="bi bi-arrow-left-square"></i>
                            Back
                        </a>
                </div>
                <div class="card-body mainbody mt-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
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
                                  <h2 class="productName">
                                    {{$product -> name}}
                                  </h2>
                                  <div class="d-flex flex-row my-3">
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

                                  <div class="mb-3">
                                    <h4 class = "productPrice">
                                        {{$product -> priceString}}
                                    </h4>
                                    <s style = "color:red">
                                        {{$product -> fakePrice}}
                                    </s>
                                  </div>

                                  <p>
                                    {{$product -> info}}
                                  </p>

                                  <h5 class = "sellerName">Sold By : {{$product -> seller -> name}}</h5>

                                  @if($product -> sellerRating != 0)
                                    <p class = "text-warning">
                                      @for($i = $product->sellerRating; $i >= 1; $i--)
                                        <i class="bi bi-star-fill"></i>
                                      @endfor
                                      @if(fmod($product->sellerRating, 1) !== 0.00)
                                          <i class="bi bi-star-half"></i>
                                      @endif
                                      @for($i = 5 - $product->sellerRating; $i >= 1; $i--)
                                          <i class="bi bi-star"></i>
                                      @endfor
                                      <span class = "text-primary">{{number_format((float)$product -> sellerRating, 1, '.','')}} ({{$product -> count}})</span>
                                    </p>
                                  @else
                                    <p class="text-primary">No reviews for the seller yet</p>
                                  @endif


                                  <hr>

                                  <div class="form-group buttons">
                                    {{-- <form action="{{route('product.buy', $product -> id)}}" method="get" class = "purchaseForm">
                                        @csrf
                                        <input type="hidden" name="product_id" value = {{$product -> id}}>
                                        <button class = "btn btn-primary" type = "submit">
                                            <i class="bi bi-cart-fill"></i>
                                            Buy Now
                                        </button>
                                    </form> --}}
                                    <a href="{{route('product.buy', $product -> id)}}" class = "btn btn-primary">
                                        <i class="bi bi-cart-fill"></i>
                                            Buy Now
                                    </a>
                                    @if($product -> wishlistbin != null && $product -> wishlistbin -> wishlist == 1)
                                      <button onclick="removeFromWishList({{$product -> id}})" class="btn m-2 btn-danger wishList-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Remove from wishlist">
                                        <i class="bi bi-heart-fill icons p-1 delete wl-{{$product->id}}"></i> <span>WishListed</span>
                                      </button>
                                    @else
                                      <button onclick="addToWishList({{$product -> id}})" class="btn m-2 btn-outline-danger wishList-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Add to wishlist">
                                        <i class="bi bi-heart icons p-1 delete wl-{{$product->id}}"></i> <span>WishList</span>
                                      </button>
                                    @endif
                                    @if($product -> wishlistbin != null && $product -> wishlistbin -> bin == 1)
                                      <button onclick="removeFromBin({{$product -> id}})" class ="btn m-2 btn-dark bin-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Remove from Bin">
                                        <i class="bi bi-trash2-fill p-1 wlb-{{$product->id}}"></i> <span>In the Bin</span>
                                      </button>
                                    @else
                                      <button onclick="addToBin({{$product -> id}})" class ="btn btn-outline-dark m-2 bin-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Add to bin">
                                        <i class="bi bi-trash2 p-1 wlb-{{$product->id}}"></i> <span>Add to bin</span>
                                      </button>
                                    @endif
                                </div>
                                <span class = "purchaseHistory">{{($product->order) ? "Last purchase : $product->order" : ""}}</span>
                            </div>
                        </main>
                            </div>
                </div>
                @if($reviews != null)
                @foreach($reviews as $key => $review)
                <div class="d-flex justify-content-center row">
                    <div class="col-md-10">
                        <div class="row p-2 bg-white border rounded items mt-2">
                            <div class="col-md-3 mt-1" id = "reviewCoverImg-{{$key}}">
                                @php
                                    $images = null;
                                    if($review -> images != null){
                                        $images = explode("->seperator<-", $review ->images);
                                        $path = '/storage/seller_uploads/'.$product -> seller_id.'/reviewImages/'.$product -> id.'/';
                                    }
                                @endphp
                                @if($images != null)
                                    <img class="card-img-top object-fit-cover border rounded" style = "height : 10rem; width : 100%" src="{{asset($path.$images[0])}}" alt="{{$product -> name}}">
                                @else
                                    <img class="card-img-top object-fit-cover border rounded" style = "height : 10rem; width : 100%" src="{{asset('assets/img/logo_small.png')}}" alt="{{$product -> name}}">
                                @endif
                            </div>
                            <div class="col-md-6 mt-1">
                                <h5>{{$review -> customer -> name}}</h5>
                                <h5>{{$review -> title}}</h5>
                                <div class="mt-1 mb-1 spec-1">{{$review -> description}}</div>
                                <div class="d-flex flex-row">
                                    @for($i = 1; $i < 6; $i++)
                                        @if($i <= $review->stars)
                                            <i class="text-warning bi bi-star-fill star-{{$i}} rate"></i>
                                        @else
                                            <i class="text-warning bi bi-star star-{{$i}} rate"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                                <div class="d-flex flex-row align-items-center">
                                    @if($images != null)
                                        <div class="d-flex justify-content-center mb-3">
                                            <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallRevImages smallRevImages-{{$key}}" id = "reviewCvrImg-{{$key}}-0" target="_blank" data-type="image" href="#">
                                              <img width="60" height="60" class="rounded-2" style = "border:2px solid #087990" src="{{asset($path.$images[0])}}"/>
                                            </a>
                                        @for($i = 1; $i < count($images) - 1; $i++)
                                            <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallRevImages smallRevImages-{{$key}}" id = "reviewCvrImg-{{$key}}-{{$i}}" target="_blank" data-type="image" href="#">
                                              <img width="60" height="60" class="rounded-2" src="{{asset($path.$images[$i])}}"/>
                                            </a>
                                        @endfor
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
            </div>
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

        $('.smallRevImages').on('click', function(e){
                e.preventDefault();
                var cvr = this.className;
                var key = cvr.charAt(cvr.length - 1);
                var len = $('.smallRevImages').length;
                var reqId = "#reviewCvrImg-";
                while(--len > -1){
                    // Removing all borders
                    $(reqId + key + "-" + len + " img").css("border", "none");
                }
                var img = "#" + $(this).attr('id') + " img";
                // Setting the border
                $(img).css("border", "3px solid #087990")
                img = $(img).attr('src');
                // Changing the source of the cover image
                cvr = '#reviewCoverImg-' + key + ' img';
                // console.log(cvr);
                $(cvr).attr('src', img);
        });

        function addToWishList(id){
            $.ajax({
                headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route('product.wishlist.add')}}',
                data :{
                    id : id,
                },
                type : 'get',
                success : function(response){
                    swal("Success", response.message, "success");
                    var link = $('.wishList-' + id);
                    var icon = $('.wl-' + id);
                    icon.removeClass('bi-heart');
                    icon.addClass('bi-heart-fill');
                    link.attr("onclick", "removeFromWishList(" + id + ")");
                    link.removeClass('btn-outline-danger');
                    link.addClass('btn-danger');
                    link.attr('title', 'Remove from WishList');
                    $('.wishList-' + id + ' span').html('WishListed');
                    // console.log(icon);
                    // icon.attr("class", "bi bi-heart icons p-1 delete wl-{{$product->id}}")
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }

        function removeFromWishList(id){
            $.ajax({
                headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route('product.wishlist.remove')}}',
                data :{
                    id : id,
                },
                type : 'get',
                success : function(response){
                    swal("Success", response.message, "success");
                    var link = $('.wishList-' + id);
                    var icon = $('.wl-' + id);
                    icon.removeClass('bi-heart-fill');
                    icon.addClass('bi-heart');
                    link.attr("onclick", "addToWishList(" + id + ")");
                    link.removeClass('btn-danger');
                    link.addClass('btn-outline-danger');
                    link.attr('title', 'Add to WishList');
                    $('.wishList-' + id + ' span').html('Add to WishList');
                    // console.log(icon);
                    // icon.attr("class", "bi bi-heart icons p-1 delete wl-{{$product->id}}")
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }

        function addToBin(id){
            $.ajax({
                headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route('product.bin.add')}}',
                data :{
                    id : id,
                },
                type : 'get',
                success : function(response){
                    swal("Success", response.message, "success");
                    var link = $('.bin-' + id);
                    var icon = $('.wlb-' + id);
                    icon.removeClass('bi-trash2');
                    icon.addClass('bi-trash2-fill');
                    link.attr("onclick", "removeFromBin(" + id + ")");
                    link.removeClass('btn-outline-dark');
                    link.addClass('btn-dark');
                    link.attr('title', 'Remove from Bin');
                    $('.bin-' + id + ' span').text('In the bin');
                    // console.log(icon);
                    // icon.attr("class", "bi bi-heart icons p-1 delete wl-{{$product->id}}")
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }

        function removeFromBin(id){
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
                    swal("Success", response.message, "success");
                    var link = $('.bin-' + id);
                    var icon = $('.wlb-' + id);
                    icon.removeClass('bi-trash2-fill');
                    icon.addClass('bi-trash2');
                    link.attr("onclick", "addToBin(" + id + ")");
                    link.removeClass('btn-dark');
                    link.addClass('btn-outline-dark');
                    link.attr('title', 'Add to Bin');
                    $('.bin-' + id + ' span').text('Add to bin');
                    // console.log(icon);
                    // icon.attr("class", "bi bi-heart icons p-1 delete wl-{{$product->id}}")
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }
    </script>
@endpush
