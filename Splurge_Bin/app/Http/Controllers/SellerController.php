<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categories;

use App\Models\Products;

use App\Models\Orders;

use App\Models\Reviews;

use App\Models\WishListBin;

use App\Models\CustomerDetails;

use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    protected $seller;
    public function __construct(){
        $this -> seller = Auth::user();
    }

    public function index(){
        $products = Products::where('seller_id',$this -> seller -> id)->get();
        foreach($products as $product){
            $rating = Reviews::where('product_id', $product->id)
                                ->avg('stars');
            $product -> rating = $rating;
        }
        $categories = Categories::all();
        return view('seller.home',compact('products', 'categories'));
    }

    public function view($id){
        $product = Products::with('seller')->find($id);
        if($product == null) return redirect()->route('home')->with('error', 'Product not found');
        if($product -> seller_id != Auth::user()->id)   return redirect()->route('home')->with('error', 'You are not authorised to access the product');
        $product -> wishList = WishListBin::where('product_id', $id)
                                ->where('wishlist', 1)
                                ->get()
                                ->count();
        $product -> bin = WishListBin::where('product_id', $id)
                                ->where('bin', 1)
                                ->get()
                                ->count();
        $product -> orders = Orders::where('product_id', $id)
                                ->get()
                                ->count();
        $reviews = Reviews::with('customer')
        ->where('product_id', $id)
        ->get();
        $rating = 0;
        if(count($reviews) != 0){
            foreach($reviews as $review){
                $rating += $review -> stars;
            }
            $rating /= count($product->reviews);
        }
        $product -> rating = $rating;
        return view('seller.product', compact('product', 'reviews'));
    }

    public function priceString($price){
        $price = (string) $price;
        $priceString = "";
        $len = strlen($price);
        for($i = $len - 1; $i >= $len - 3; $i--){
            $priceString .= $price[$i];
        }
        $flag = $len % 2;
        for($i = $len - 4; $i >= 0; $i--){
            $priceString .= (($i % 2 == $flag) ? "," : "").$price[$i];
        }
        $priceString = "Rs. ". strrev($priceString);
        return $priceString;
    }

    public function orders(){
        $id = Auth::user()->id;
        $orders = Orders::with('product', 'customer')
                    ->where('seller_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $count = 0;
        foreach($orders as $order){
            $order -> product -> priceString = $this -> priceString($order -> product -> price);
            $rating = Reviews::where('product_id', $order -> product_id)
                                ->avg('stars');
            $order -> product -> rating = $rating;
            if($order -> status == 0)   $count++;
        }
        $orders -> count = $count;
        $categories = Categories::all();
        return view('seller.orders', compact('orders', 'categories'));
    }

    public function viewOrder($id){
        $order = Orders::with('product', 'customer')
                        ->find($id);
        if($order == null)  return redirect()->route('seller.orders')->with('error', 'Order not found');
        $order -> product -> priceString = $this -> priceString($order -> product -> price);
        $order -> product -> rating = Reviews::where('product_id', $order -> product_id)
                                                ->avg('stars');
        $order -> details = CustomerDetails::withTrashed()->find($order -> customer_detail_id);
        $review = Reviews::where('customer_id', $order -> customer_id)
                            ->where('product_id', $order->product_id)
                            ->get();
        if(count($review) != 0){
            $review = $review[0];
            $order -> review = $review;
        }
        // dd($order);
        return view('seller.orderProduct', compact('order'));
    }

    public function acceptOrder(Request $request){
        $id = $request -> id;
        $order = Orders::find($id);
        if($order == null)  return response()->json(['result' => 'false', 'message' => 'Order not found'], 404);
        if($order -> status != 0)   return response()->json(['result' => 'false', 'message' => 'Order already accepted'], 400);
        $order -> status = 1;
        $order -> save();
        return response()->json(['result' => 'true']);
    }

    public function acceptAllOrders(){
        try{
            $orders = Orders::where('seller_id', Auth::user()->id)
                                ->get();
            $count = 0;
            foreach($orders as $order){
                if($order -> status == 0){
                    $order -> status = 1;
                    $order -> save();
                    $count++;
                }
            }
            if($count == 0) return redirect()->route('seller.orders');
            return redirect()->route('seller.orders')->with('success', "$count orders accepted");
        } catch(Exception $e){
            return redirect()->route('seller.orders')->with('error', "Something went wrong");
        }
    }

    public function profile(){
        $profile = Auth::user();
        $products = Products::with('reviews', 'wlBin', 'orders')
                            ->where('seller_id', $profile -> id)
                            ->get();
        $orderCount = 0;
        $binCount = 0;
        $wlCount = 0;
        $revenue = 0;
        $rating = 0;
        $revCount = 0;
        // dd($products);
        foreach($products as $product){
            if(count($product -> orders) != 0){
                $orderCount += count($product -> orders);
                foreach($product -> orders as $order){
                    $revenue += ($order -> status == 1) ? $product -> price : 0;
                }
            }
            if(count($product -> wlBin) != 0){
                foreach($product -> wlBin as $wlBin){
                    $wlCount += $wlBin -> wishlist;
                    $binCount += $wlBin -> bin;
                }
            }
            if(count($product -> reviews) != 0){
                $revCount += count($product -> reviews);
                foreach($product -> reviews as $review){
                    $rating += $review -> stars;
                }
            }
        }
        if($revCount != 0)  $rating /= $revCount;
        $profile -> orderCount = $orderCount;
        $profile -> binCount = $binCount;
        $profile -> wlCount = $wlCount;
        $profile -> revenue = substr($this -> priceString($revenue), 4);
        $profile -> rating = number_format((float)$rating, 1, '.', '');
        $profile -> productCount = count($products);
        // dd($profile);
        return view('seller.profile', compact('profile'));
    }

}
