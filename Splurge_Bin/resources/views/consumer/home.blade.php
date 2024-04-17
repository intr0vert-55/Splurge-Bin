@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <input class="form-control mr-sm-2" id = "searchBar" type="search" placeholder="Search" aria-label="Search" style = "width:15rem">
                        <select id="categories" class="form-select" style = "width:15rem">
                            <option selected value = '-1'>All</option>
                            @foreach($categories as $category)
                                <option value = {{$category -> id}}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body mainbody">
                    {{-- Displaying all items
                            foreach loop should be here with a condition that adds a div class for each 4 items
                            --}}
                    {{-- <div class="d-flex flex-row"> --}}
                        {{-- 4 items --}}

                    @foreach($products as $key => $product)
                        @if($key % 4 == 0)
                            @if($key != 0)
                                </div>
                            @endif
                            <div class="d-flex flex-row">
                        @endif
                        <div class="card m-4 items cat-{{$product -> category_id}}" style="width: 18rem;">
                            @php
                                $images = explode("->seperator<-", $product -> image);
                            @endphp
                            <img class="card-img-top object-fit-cover border rounded" style = "height : 15rem; width : 100%" src="{{asset('/storage/seller_uploads/'.$product->seller_id.'/'.$product->id.'/'.$images[0])}}" alt="{{$product -> name}}">
                            <div class="card-body">
                              <h5 class="card-title">{{$product -> name}}</h5>
                              <p class="card-text">
                                @php
                                    $info = $product -> info;
                                    $limit = 145;
                                    $extra = (strlen($info) < $limit + 5) ? "" : "....";
                                    $info = substr($info, 0, $limit).$extra;
                                    echo $info;
                                @endphp
                              </p>
                              <div class="d-flex justify-content-between">
                                  <h6 class = "card-text">
                                    Rs. {{$product -> price}}
                                </h6>
                                <span>
                                        @if($product->rating != 0)
                                        <h6 class = 'review-stars text-white card-text'>{{number_format((float)$product -> rating, 1, '.','')}} <i class="bi bi-star-fill"></i></h6>
                                        @endif
                                </span>
                              </div>
                              <div class="d-flex justify-content-between">
                                  <a href="{{route('products.consumer.view', $product -> id)}}" class="btn btn-primary mr-2 buy">Buy</a>
                                  <div class="form-group">
                                    @if($product -> wishlistbin != null && $product -> wishlistbin -> wishlist == 1)
                                      <button onclick="removeFromWishList({{$product -> id}})" class="btn m-2 link-danger wishList-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Remove from wishlist">
                                        <i class="bi bi-heart-fill icons p-1 delete wl-{{$product->id}}"></i>
                                      </button>
                                    @else
                                      <button onclick="addToWishList({{$product -> id}})" class="btn m-2 link-danger wishList-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Add to wishlist">
                                        <i class="bi bi-heart icons p-1 delete wl-{{$product->id}}"></i>
                                      </button>
                                    @endif
                                    @if($product -> wishlistbin != null && $product -> wishlistbin -> bin == 1)
                                      <button onclick="removeFromBin({{$product -> id}})" class ="btn m-2 bin bin-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Remove from bin">
                                        <i class="bi bi-trash2-fill icons p-1 wlb-{{$product->id}}"></i>
                                      </button>
                                    @else
                                      <button onclick="addToBin({{$product -> id}})" class ="btn m-2 bin bin-{{$product -> id}}" data-toggle = "tooltip" data-placement="bottom" title = "Add to bin">
                                        <i class="bi bi-trash2 icons p-1 wlb-{{$product->id}}"></i>
                                      </button>
                                    @endif
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
@endsection


@push('script')
    {{-- Swal for showing that the product is added to wishlist or bin --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

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
                    link.attr('title', 'Remove from WishList');
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
                    link.attr('title', 'Add to WishList');
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
                    link.attr('title', 'Remove from Bin');
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
                    link.attr('title', 'Add to Bin');
                    // console.log(icon);
                    // icon.attr("class", "bi bi-heart icons p-1 delete wl-{{$product->id}}")
                },
                error : function(jqXHR, response){
                    swal("Error", response.message, "error");
                },
            });
        }


        $(document).ready(function(){

            // Search
            // Search bar written with the help of ChatGPT

            $('#searchBar').on('input', function(){
                filterContent();
            });

            $("#categories").change(function(){
                filterContent();
            });

            function filterContent(){
                var searchTerm = $('#searchBar').val().toLowerCase();
                var value = $('#categories').val();
                var targetClass = "cat-" + value;
                $('.items').each(function(){
                    var className = $(this).attr("class");
                    var cardText = $(this).text().toLowerCase();
                    if((value == -1 && cardText.indexOf(searchTerm) != -1) || (className.includes(targetClass) && cardText.indexOf(searchTerm) != -1)){
                        $(this).show();
                    } else{
                        $(this).hide();
                    }
                });
            }
        });


    </script>

@endpush
