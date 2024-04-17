@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                        <h2 class = "productName">{{$order -> product -> name}}</h2>
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
                                    $images = $order -> product -> image;
                                    $images = explode("->seperator<-", $images);
                                    $path = '/storage/seller_uploads/'.$order ->product->seller_id.'/'.$order->product->id.'/';
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
                                        @if($order -> product -> rating != 0)
                                            @for($i = $order -> product->rating; $i >= 1; $i--)
                                              <i class="bi bi-star-fill"></i>
                                            @endfor
                                            @if(fmod($order -> product -> rating, 1) !== 0.00)
                                                <i class="bi bi-star-half"></i>
                                            @endif
                                            @for($i = 5 - $order -> product -> rating; $i >= 1; $i--)
                                                <i class="bi bi-star"></i>
                                            @endfor
                                        @endif
                                      <span class="ms-1 text-primary">
                                        @if($order -> product -> rating != 0)
                                            {{number_format((float)$order -> product -> rating, 1, '.','')}} ({{count($order -> reviews)}})
                                        @else
                                            Be the first to review this product
                                        @endif
                                      </span>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <h4 class = "productPrice">
                                        {{$order -> product -> priceString}}
                                    </h4>
                                  </div>

                                  <h5 class = "sellerName">Sold By : {{$order -> seller -> name}}</h5>
                                  <hr>
                                  <p>
                                      Delivering to : <br>
                                      {{$order -> details -> address}}<br>
                                      {{$order -> details -> state}}<br>
                                      {{$order -> details -> country}}<br>
                                      {{$order -> details -> mobile}}<br>
                                  </p>
                                  <hr>
                                  <p>
                                    Order status :
                                    <span class = 'h4'>
                                    @switch($order -> status)
                                        @case(0)
                                            <span class = "text-warning">
                                                Order request sent
                                            </span>
                                            @break
                                        @case(1)
                                            <span class = "text-success">
                                                Order Accepted ✔
                                            </span>
                                            @break
                                        @default
                                            <span class = "text-secondary">
                                                Unknown
                                            </span>
                                    @endswitch
                                    </span>
                                  </p>
                                  <a href="{{route('orders')}}" class = "btn btn-outline-dark">
                                    <i class="bi bi-arrow-left"></i>
                                    Orders
                                  </a>
                                  @if($order -> status == 0)
                                    <button onclick = "cancelOrder({{$order -> id}})" class = "btn btn-outline-danger">
                                      Cancel Order
                                      <i class="bi bi-x-lg"></i>
                                    </button>
                                 @endif
                                 @if($order -> review == null && $order -> status != 0)
                                    <button type = "button" class = "btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Write a review
                                        <i class="bi bi-journal-text"></i>
                                    </button>
                                @endif
                                </div>
                        </main>
                    </div>
                </div>
            </div>
            @if($order -> review != null)
                <div class="d-flex justify-content-center row">
                    <div class="col-md-10">
                        <div class="row p-2 bg-white border rounded items mt-2">
                            <div class="col-md-3 mt-1" id = "reviewCoverImg">
                                @php
                                    $images = null;
                                    if($order -> review -> images != null){
                                        $images = explode("->seperator<-", $order ->review ->images);
                                        $path = '/storage/seller_uploads/'.$order -> seller -> id.'/reviewImages/'.$order -> product -> id.'/';
                                    }
                                @endphp
                                @if($images != null)
                                    <img class="card-img-top object-fit-cover border rounded" style = "height : 10rem; width : 100%" src="{{asset($path.$images[0])}}" alt="{{$order -> product -> name}}">
                                @else
                                    <img class="card-img-top object-fit-cover border rounded" style = "height : 10rem; width : 100%" src="{{asset('assets/img/logo_small.png')}}" alt="{{$order -> product -> name}}">
                                @endif
                            </div>
                            <div class="col-md-6 mt-1">
                                <h5>{{$order -> product -> name}}</h5>
                                <h5>{{$order -> review -> title}}</h5>
                                <div class="mt-1 mb-1 spec-1">{{$order -> review -> description}}</div>
                                <div class="d-flex flex-row">
                                    @for($i = 1; $i < 6; $i++)
                                        @if($i <= $order->review->stars)
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
                                            <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallRevImages" id = "reviewCvrImg0" target="_blank" data-type="image" href="#">
                                              <img width="60" height="60" class="rounded-2" style = "border:2px solid #087990" src="{{asset($path.$images[0])}}"/>
                                            </a>
                                        @for($i = 1; $i < count($images) - 1; $i++)
                                            <a data-fslightbox="mygalley" class="border mx-1 rounded-2 smallRevImages" id = "reviewCvrImg{{$i}}" target="_blank" data-type="image" href="#">
                                              <img width="60" height="60" class="rounded-2" src="{{asset($path.$images[$i])}}"/>
                                            </a>
                                        @endfor
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column mt-4">
                                    <button type = "button" class = "btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Edit your review
                                        <i class="bi bi-journal-text"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if($order -> review == null)
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">
            Write your review
        </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('review.add', $order->id)}}" onsubmit="return ratingExists()" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="form-group">
                        <p>Rating</p>
                        <h4>
                        <i class="text-warning bi bi-star star-1 rate"></i>
                        <i class="text-warning bi bi-star star-2 rate"></i>
                        <i class="text-warning bi bi-star star-3 rate"></i>
                        <i class="text-warning bi bi-star star-4 rate"></i>
                        <i class="text-warning bi bi-star star-5 rate"></i>
                        <br>
                        </h4>
                        <input type="text" hidden class = "stars" id = "stars" name = "stars">

                    </div>
                    <label for="exampleFormControlInput1" class="form-label">Title</label>
                    <input type="text" name = "title" class="form-control title" id="exampleFormControlInput1" placeholder = "Title (optional)">
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                    <textarea class="form-control description" name = "description" id="exampleFormControlTextarea1" rows="3" placeholder="Write about your experience with the product" required></textarea>
                  </div>
                  <div class="mb-3">
                      <label for="formFile" class="form-label">Add Images</label>
                      <input class="form-control" type="file" accept="image/*" id="images" name = "images[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save">Save</button>
                </div>
            </form>
      </div>
    </div>
  </div>
  @else
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">
            Edit your review
        </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('review.update', $order -> review->id)}}" onsubmit="return ratingExists()" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="form-group">
                        <p>Rating</p>
                        <h4>
                        @for($i = 1; $i < 6; $i++)
                            @if($i <= $order->review->stars)
                                <i class="text-warning bi bi-star-fill star-{{$i}} rate"></i>
                            @else
                                <i class="text-warning bi bi-star star-{{$i}} rate"></i>
                            @endif
                        @endfor
                        {{-- <i class="text-warning bi bi-star star-2 rate"></i>
                        <i class="text-warning bi bi-star star-3 rate"></i>
                        <i class="text-warning bi bi-star star-4 rate"></i>
                        <i class="text-warning bi bi-star star-5 rate"></i> --}}
                        <br>
                        </h4>
                        <input type="text" hidden class = "stars" id = "stars" name = "stars" value = {{$order -> review -> stars}}>

                    </div>
                    <label for="exampleFormControlInput1" class="form-label">Title</label>
                    <input type="text" name = "title" class="form-control title" id="exampleFormControlInput1" placeholder = "Title (optional)" value = {{$order -> review -> title}}>
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                    <textarea class="form-control description" name = "description" id="exampleFormControlTextarea1" rows="3" placeholder="Write about your experience with the product" required>{{$order -> review -> description}}</textarea>
                  </div>
                  <div class="mb-3">
                      <label for="formFile" class="form-label">Add Images</label>
                      <input class="form-control" type="file" accept="image/*" id="images" name = "images[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save">Save</button>
                </div>
            </form>
      </div>
    </div>
  </div>
  @endif
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
            $('.smallRevImages').on('click', function(e){
                e.preventDefault();
                var len = $('.smallRevImages').length;
                var reqId = "#reviewCvrImg";
                while(--len > -1){
                    // Removing all borders
                    $(reqId + len + " img").css("border", "none");
                }
                var img = "#" + $(this).attr('id') + " img";
                // Setting the border
                $(img).css("border", "3px solid #087990")
                img = $(img).attr('src');
                // Changing the source of the cover image
                $('#reviewCoverImg img').attr('src', img);

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

        function cancelOrder(id){
            swal({
              title: "Are you sure?",
              text: "You cannot revert the process",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    headers : {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route('order.cancel')}}',
                    method : 'delete',
                    data : {
                        id : id,
                    },
                    success : function(response){
                        swal("Order Cancelled", {
                          icon: "success",
                        }).then((isOK) => {
                                // Should change the location
                                window.location = '{{route('orders')}}';
                        });
                    },
                    error : function(response){
                        swal("Error!", response.responseJSON.message, "error");
                    }
                });
              }
            });
        }

        $('.rate').click(function(){
            let star = this.className;
            // console.log(star);
            let starval = star.charAt(29);
            if(star.includes('bi-star-fill'))
                starval = star.charAt(34);
            // console.log(starval);
            document.getElementById('stars').value = starval;
            for(let i = 0;i<5;i++){
                let name = '.star-'+(i+1);
                // console.log(name);
                let fillClass = 'bi bi-star-fill star-'+(i+1) + ' rate';
                let nofillClass = 'bi bi-star star-'+(i+1) + ' rate';
                let starClass = $(name)
                // console.log(starClass.attr('class'));
                let classOfStar = starClass.attr('class');
                if(i<starval){
                    starClass.removeClass(nofillClass);
                    starClass.addClass(fillClass);
                }
                else{
                    // console.log('Inside else '+ classOfStar);
                    if(classOfStar.includes("bi-star-fill")){
                        starClass.removeClass(fillClass);
                        starClass.addClass(nofillClass);
                    }
                }
            }
        });

        $(function(){
            $("#images").on("change", function(){
                var files = $("#images").get(0).files;
                if(files.length > 2){
                    swal("Too many images", "You can't add more than 2 images", "warning");
                    $("#images").val('');
                }
            })
        });

        function ratingExists(){
            var val = $('#stars').val();
            if(val == 0){
                swalAlert();
                return false;
            }
            return true;
        }

        function swalAlert(){
            swal("Error", "Give your rating to continue", "warning");
        }


    </script>
@endpush
