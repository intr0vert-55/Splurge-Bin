@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header">
                    <h3>Your Products</h3>
                </div>
                <div class="card-body mainbody mt-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{route('products.add')}}" class="px-3 btn btn-primary">
                                <i class="bi bi-plus-circle"></i>
                                Add a product
                            </a>
                            <div class="d-flex justify-content-between">
                                <input class="form-control mr-sm-2" id = "searchBar" type="search" placeholder="Search" aria-label="Search" style = "width:15rem; margin-right: 2rem;">
                                <select id="categories" class="form-select" style = "width:15rem">
                                    <option selected value = '-1'>All</option>
                                    @foreach($categories as $category)
                                        <option value = {{$category -> id}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
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
                            <img class="card-img-top object-fit-cover border rounded" style = "height : 15rem; width : 100%" src="{{asset('/storage/seller_uploads/'.Auth::user()->id.'/'.$product->id.'/'.$images[0])}}" alt="{{$product -> name}}">
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
                                  <a href="{{route('products.seller.view', $product -> id)}}" class="btn btn-primary mr-2 h-25">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                                  <div class="form-group">
                                      <a href="{{route('products.edit', $product -> id)}}" class="m-2" data-toggle = "tooltip" data-placement="bottom" title = "Edit">
                                        <i class="bi bi-pencil-square icons p-1"></i>
                                      </a>
                                      <button onclick = "deleteProduct({{$product -> id}})" class ="btn m-2 link-danger" data-toggle = "tooltip" data-placement="bottom" title = "Delete">
                                        <i class="bi bi-trash-fill icons p-1 delete"></i>
                                      </button>
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


@push("script")

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type = "text/javascript">
    function deleteProduct(id){
        swal({
          title: "Are you sure?",
          text: "You will not be able to recover this again",
          icon: "warning",
          dangerMode: true,
          buttons: {
            cancel: true,
            confirm: true,
            confirm: {
                class: "danger",
                text: "Yes, delete it!",
            },
          },
          closeModal: false
        }).then((isConfirm) => {
            if(!isConfirm)  return;
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : '{{route('products.delete')}}',
                data : {
                    id : id,
                },
                type : 'get',
                success : function(){
                    swal("Deleted!", "The product is deleted successfully", "success").then((isOK) => {
                        window.location.reload();
                    });
                },
                error : function(xhr, ajaxOptions, thrownError){
                    swal("Error!", "Something went wrong", "error");
                }
            });
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


{{-- function deleteProduct(id){
    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this again",
      type: "warning",
      buttons: {
        cancel: true,
        confirm: true,
        confirm: {
            class: "danger",
            text: "Yes, delete it!",
        },
      },
    //   showCancelButton: true,
    //   confirmButtonClass: "btn-danger",
    //   confirmButtonText: "Yes, delete it!",
    //   cancelButtonText: "Cancel",
    //   cancelButtonClass: "info",
      closeOnConfirm: false
    }).then((isConfirm) => {
        if(!isConfirm)  return;
        $.ajax({
            headers : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : '{{route('products.delete')}}',
            data : {
                id : id,
            },
            type : 'post',
            success : function(){
                swal("Deleted!", "The Project is deleted successfully", "success");
            },
            error : function(xhr, ajaxOptions, thrownError){
                swal("Error!", "Something went wrong", "error");
            }
        });
    });
} --}}
