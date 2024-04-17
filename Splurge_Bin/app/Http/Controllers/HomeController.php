<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\SellerController;

use App\Http\Controllers\ConsumerController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user() -> is_seller == 1){
            $sellerController = new SellerController();
            return $sellerController -> index();
        }
        else{
            $consumerController = new ConsumerController();
            return $consumerController -> index();
        }
    }
}
