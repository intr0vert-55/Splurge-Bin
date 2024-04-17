@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>Add Product</h3>
                        <a class = "btn" href="{{route('home')}}">
                            Back
                            <i class="bi bi-arrow-left-square"></i>
                        </a>
                    </div>
                </div>
                <form action="{{isset($product) ? route('products.update', $product -> id) : route('products.store')}}" enctype="multipart/form-data" method="post">
                    @csrf
                <div class="card-body mainbody p-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"><h6>Name</h6></label>
                        <input type="text" name = "name" class="form-control" id="exampleFormControlInput1" value = "{{(isset($product)? $product -> name : "")}}" required>
                    </div>
                    <br>
                    <div class="form-floating">
                        <textarea class="form-control" name = "info" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" required>{{(isset($product)? $product -> info : "")}}</textarea>
                        <label for="floatingTextarea2"><h6>Info</h6></label>
                      </div>
                      <br>
                      <div class="row g-2">
                        <div class="col-md">
                          <div class="form-floating">
                            <input type="number" name = "price" class="form-control" id="floatingInputGrid" placeholder="name@example.com" value = "{{(isset($product)? $product -> price : "")}}" required>
                            <label for="floatingInputGrid"><h6>Price (in Rs.)</h6></label>
                          </div>
                        </div>
                        <div class="col-md">
                          <div class="form-floating">
                            <select class="form-select" name = "category" id="floatingSelectGrid" aria-label="Floating label select example">
                              <option selected>Select the category of the product</option>
                              @foreach($categories as $category)
                                <option value={{$category -> id}} {{(isset($product) ? (($category -> id == $product -> category_id) ? "selected" : "") : "")}}>{{$category -> name}}</option>
                              @endforeach
                            </select>
                            <label for="floatingSelectGrid"><h6>Category</h6></label>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="formFile" class="form-label">Images</label>
                        <input class="form-control" type="file" accept="image/*" id="images" name = "images[]" multiple {{(isset($product) ? "" : "required")}}>
                      </div>
                      <div class="mb3 mt-3">
                        <button class = "btn btn-primary" type="submit">
                            @isset($product)
                                Update
                                <i class="bi bi-pencil-square icons p-1"></i>
                                </button>
                            @else
                                Add
                                <i class="bi bi-plus-lg"></i>
                                </button>
                                <button class = "btn btn-warning" type="reset">
                                    Reset
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            @endisset
                      </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function(){
            $("#images").on("change", function(){
                var files = $("#images").get(0).files;
                if(files.length > 4){
                    swal("Too many images", "You can't add more than 4 images", "warning");
                    $("#images").val('');
                }
            })
        });
    </script>


@endpush

