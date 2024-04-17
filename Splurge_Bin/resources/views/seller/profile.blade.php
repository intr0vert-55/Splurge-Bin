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
                      <div class="text-center">
                        <div class="card-body">
                          <h5 class="card-title">STATS OF YOUR {{$profile -> productCount}} PRODUCTS</h5>
                        </div>
                      </div>
                    <div class="row p-3 d-flex justify-content-around">
                        <div class="card items profile-cards-3 wishlist">
                            <h5 class="card-title">WishList</h5>
                            <div class="card-body d-flex justify-content-center mt-5">
                              <h1><i class="bi bi-heart-fill"></i>{{$profile -> wlCount}}</h1>
                            </div>
                          </div>
                          <div class="card items profile-cards-3 cover bin">
                            <h5 class="card-title">Bin</h5>
                            <div class="card-body d-flex justify-content-center mt-5">
                              <h1><i class="bi bi-trash2-fill"></i>{{$profile -> binCount}}</h1>
                            </div>
                          </div>
                          <div class="card items profile-cards-3 orders">
                            <h5 class="card-title">Orders</h5>
                            <div class="card-body d-flex justify-content-center mt-5">
                              <h1><i class="bi bi-cart-fill"></i>{{$profile -> orderCount}}</h1>
                            </div>
                          </div>
                    </div>
                    <div class="row p-3 d-flex justify-content-around">
                        <div class="card items profile-cards-2 rating">
                            <h5 class="card-title">Rating</h5>
                            <div class="card-body d-flex justify-content-center mt-2">
                              <h1><i class="bi bi-star-fill"></i>{{$profile -> rating}}</h1>
                            </div>
                          </div>
                        <div class="card items profile-cards-2 revenue">
                            <h5 class="card-title">Revenue</h5>
                            <div class="card-body d-flex justify-content-center mt-2">
                              <h1><i class="bi bi-currency-rupee"></i>{{$profile -> revenue}}</h1>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
