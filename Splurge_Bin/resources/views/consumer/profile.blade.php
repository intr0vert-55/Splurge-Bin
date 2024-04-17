@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="bg-white rounded">
            <div class="card">
                <div class="card-body mt-4">
                    <div class="card-header">
                        <div class="card-body">
                          <h1>Welcome, {{$profile -> name}}</h1>
                        </div>
                      </div>
                      @if(isset($profile -> error))
                        <div class="alert alert-danger d-flex justify-content-between" role = "alert">
                            <p>No address found. Add an address to order products</p>
                            <button type="button" class = "btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">Add an address <i class="bi bi-plus-circle"></i></button>
                        </div>
                      @endif
                      <div class="text-center">
                        <div class="card-body">

                        </div>
                      </div>
                    <div class="row p-3 d-flex justify-content-around">
                        <div class="card items profile-cards-2 rating">
                            <h5 class="card-title">Orders</h5>
                            <div class="card-body d-flex justify-content-center mt-2">
                              <h1><i class="bi bi-cart-fill"></i>{{$profile -> orderCount}}</h1>
                            </div>
                          </div>
                        <div class="card items profile-cards-2 revenue">
                            <h5 class="card-title">Money Spent</h5>
                            <div class="card-body d-flex justify-content-center mt-2">
                              <h1><i class="bi bi-currency-rupee"></i>{{$profile -> moneySpent}}</h1>
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
                    <button type="button" class="btn btn-primary save" onclick = "addAddress()">Add</button>
                </div>
            </form>
      </div>
    </div>
  </div>
@endsection


@push('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

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

    </script>
@endpush
