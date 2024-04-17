<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;

use App\Models\WishListBin;

use App\Models\CustomerDetails;

use App\Models\Orders;

use App\Models\Reviews;

use App\Models\Categories;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\DetailRequest;

use App\Http\Requests\ReviewRequest;

use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class ConsumerController extends Controller
{
    public function index(){
        $products = Products::with('wishListBin')->get();
        $categories = Categories::all();
        foreach($products as $product){
            $rating = Reviews::where('product_id', $product->id)
                                ->avg('stars');
            $product -> rating = $rating;
        }
        // dd($products);
        return view('consumer.home',compact('products', 'categories'));
    }

    public function view($id){
        $product = Products::with('seller', 'wishListBin')->find($id);
        $reviews = Reviews::with('customer')
                            ->where('product_id', $id)
                            ->get();
        // dd($product);
        $order = Orders::select('created_at')
                        ->where('product_id', $id)
                        ->where('customer_id', Auth::user()->id)
                        ->orderBy('created_at')
                        ->take(1)
                        ->get();
        if(count($order) != 0){
            $order = Carbon::parse($order[0] -> created_at);
            $order = $order->format('d/m/y h:m');
            $product -> order = $order;
        }
        $products = Products::with('reviews')
                                    ->where('seller_id', $product->seller_id)
                                    ->get();
        $sellerRating = 0;
        $count = 0;
        foreach($products as $item){
            if($item -> reviews != null){
                foreach($item -> reviews as $review){
                    $sellerRating += $review -> stars;
                    $count++;
                }
            }
        }
        if($sellerRating != 0)  $sellerRating /= $count;
        $product -> sellerRating = $sellerRating;
        $product -> count = $count;
        $rating = 0;
        if(count($reviews) != 0){
            foreach($reviews as $review){
                $rating += $review -> stars;
            }
            $rating /= count($product->reviews);
        }
        $product -> rating = $rating;
        $product -> priceString = $this -> priceString($product -> price);
        $product -> fakePrice = $this -> priceString($product -> price * 102/100);
        return view('consumer.product', compact('product', 'reviews'));
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

    public function wishlist(){
        $products = Products::with('wishList', 'seller', 'reviews')
        ->get();
        $count = 0;
        foreach($products as $product){
            $product -> priceString = $this -> priceString($product -> price);
            $rating = 0;
            if($product -> wishList != null && $product -> wishList -> wishlist == 1)   $count++;
            if(count($product -> reviews) != 0){
                foreach($product -> reviews as $review){
                    $rating += $review -> stars;
                }
                $rating /= count($product -> reviews);
            }
            $product -> rating = $rating;
        }
        $products -> count = $count;
        $categories = Categories::all();
        return view('consumer.wishlist', compact('products', 'categories'));
    }

    public function bin(){
        $products = Products::with('bin', 'seller')
        ->get();
        $price = 0;
        $count = 0;
        foreach($products as $product){
            $product -> priceString = $this -> priceString($product -> price);
            if($product -> bin != null){
                $price += $product -> price;
                $count++;
            }
            $rating = 0;
            if(count($product -> reviews) != 0){
                foreach($product -> reviews as $review){
                    $rating += $review -> stars;
                }
                $rating /= count($product -> reviews);
            }
            $product -> rating = $rating;
        }
        $products -> price = ($price != 0) ? $this -> priceString($price) : 0;
        $products -> count = $count;
        $details = CustomerDetails::where('customer_id', Auth::user()->id)->get();
        return view('consumer.bin', compact('products', 'details'));
    }

    public function buy($product_id){
        $product = Products::with('seller', 'wishListBin')->find($product_id);
        $product -> priceString = $this -> priceString($product -> price);
        $product -> fakePrice = $this -> priceString($product -> price * 102/100);
        $details = CustomerDetails::where('customer_id', Auth::user()->id)->get();
        return view('consumer.buy', compact('details', 'product'));
    }

    public function addAddress(DetailRequest $request){
        $customer_id = Auth::user()->id;
        try{
            $customerDetails = [
                'customer_id' => $customer_id,
                'mobile' => $request -> input('mobile'),
                'address' => $request -> input('address'),
                'state' => $request -> input('state'),
                'country' => $request -> input('country')
            ];
            CustomerDetails::create($customerDetails);
            return response()->json(['result' => true]);
        } catch(Exception $e){
            return response()->json(['result' => false], 500);
        }

        // Reminder : We once got error for using -> instead of => above and wasted around 4 hours in that error
        // saying Get method is not supported for this route
        // It also occurs if you use $request inside a method without having that as a parameter
        // It will also occur if you try eloquent query by using get like for deletion you should give find and then delete
    }

    public function updateAddress(DetailRequest $request){
        $customer_id = Auth::user()->id;
        try{
            $customerDetailsid = $request -> id;
            $customerDetails = CustomerDetails::findOrFail($customerDetailsid);
            $updatedCustomerDetails = [
                'customer_id' => $customer_id,
                'mobile' => $request -> mobile,
                'address' => $request -> address,
                'state' => $request -> state,
                'country' => $request -> country
            ];
            $customerDetails->update($updatedCustomerDetails);
            return response()->json(['result' => true]);
        } catch(Exception $e){
            return response()->json(['result' => false], 500);
        }
    }

    public function deleteAddress(Request $request){
        try{
            $id = $request -> id;
            CustomerDetails::findOrFail($id)->delete();
            return response()->json(['result' => true]);
        } catch(Exception $e){
            return response()->json(['result' => false], 404);
        }
    }

    public function purchase(Request $request, $id){
        $product = Products::find($id);
        $details = CustomerDetails::find($request -> detailId);
        if($product == null || $details == null){
            return redirect()
                    ->route('product.buy', $id)
                    ->with('error', 'Error! Refresh the page and try again');
        }
        $orders = [
            'customer_id' => $details -> customer_id,
            'product_id' => $product -> id,
            'seller_id' => $product -> seller_id,
            'customer_detail_id' => $details -> id,
            'status' => 0,
        ];

        $order = Orders::create($orders);
        return redirect()->route('orders.view', $order -> id)->with('success', 'Order Placed successfully');

    }

    public function orders(){
        $id = Auth::user()->id;
        $orders = Orders::with('product', 'seller', 'reviews')
                    ->where('customer_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        foreach($orders as $order) {
            $order -> product -> priceString = $this -> priceString($order -> product -> price);
            $rating = Reviews::where('product_id', $order -> product_id)
                                ->avg('stars');
            $order -> product -> rating = $rating;
        }
        // dd($orders);
        $categories = Categories::all();
        return view('consumer.orders', compact('orders', 'categories'));
    }

    public function viewOrder($id){
        $order = Orders::with('product', 'seller', 'review')
                        ->find($id);
        // dd($order);
        // Gotta review and write accordingly for the problem with details....
        if($order == null){
            return redirect()->route('orders')->with('error', 'Order not found');
        }
        $order -> details = CustomerDetails::withTrashed()->find($order -> customer_detail_id);
        $order -> product -> priceString = $this -> priceString($order -> product -> price);
        $order -> product -> rating = Reviews::where('product_id', $order -> product_id)
                                                ->avg('stars');
        return view('consumer.orderProduct', compact('order'));
    }

    public function cancelOrder(Request $request){
        $id = $request -> id;
        $order = Orders::find($id);
        if($order == null){
            return response()->json(["result" => 'false', "message" => 'Invalid order'], 404);
        }
        if($order -> status != 0){
            return response()->json(["result" => 'false', "message" => 'Order cannot be cancelled anymore'], 400);
        }
        $order -> delete();
        return response()->json(["result" => 'true']);
    }

    public function buyAll(Request $request){
        $id = $request -> detailId;
        if($id == null){
            return redirect()->route('bin')->with('error', 'Enter an address');
        }
        $detail = CustomerDetails::find($id);
        if($detail == null){
            return redirect()->route('bin')->with('error', 'Address not found');
        }
        $products = Products::with('bin')
        ->get();
        $count = 0;
        foreach($products as $product){
            if($product -> bin != null){
                $count++;
                $order = [
                    'customer_id' => $detail -> customer_id,
                    'product_id' => $product -> id,
                    'seller_id' => $product -> seller_id,
                    'customer_detail_id' => $detail -> id,
                    'status' => 0,
                ];
                Orders::create($order);
            }
        }
        return redirect() -> route('orders') -> with('success', "$count orders placed successfully");
    }

    public function addReview(ReviewRequest $request, $id){
        // Check if the id already exists
        $order = Orders::find($id);
        if($order == null)  return redirect()->route('orders')->with('error', 'Order not found');
        $reviewExist = Reviews::where('customer_id', $order->customer_id)
                                ->where('product_id', $order->product_id)
                                ->get();
        if(count($reviewExist) != 0)    return redirect()->route('orders.view', $order -> id)->with('error', 'You already wrote a review for this product');
        $review = [
            'title' => $request -> title,
            'description' => $request -> description,
            'stars' => $request -> stars,
            'customer_id' => $order -> customer_id,
            'product_id' => $order -> product_id,
        ];

        if($request->hasFile('images')){
            $defPath = "/public/seller_uploads/".$order->seller_id."/reviewImages/".$order -> product['id'];

            Storage::makeDirectory($defPath);

            $images = $request -> file('images');

            $imageColl = "";

            foreach($images as $image){
                $imageName = $image -> getClientOriginalName();
                $imageColl .= $imageName."->seperator<-";
                $imagePath = $defPath."/$imageName";
                Storage::put($imagePath, file_get_contents($image));
            }

            $review['images'] = $imageColl;
        }

        // //Gotta let the user add images

        Reviews::create($review);
        return redirect()->route('orders.view', $order -> id)->with('success', 'Review added successfully');
    }

    public function updateReview(ReviewRequest $request, $id){
        $review = Reviews::find($id);
        if($review == null) return redirect()->route('orders')->with('error', 'Review not found');

        $updatedReview = [
            'title' => $request -> title,
            'description' => $request -> description,
            'stars' => $request -> stars,
            'customer_id' => $review -> customer_id,
            'product_id' => $review -> product_id,
        ];

        $product = Products::find($review -> product_id);

        $updatedReview['images'] = $review -> images;

        if($request->hasFile('images')){

            $defPath = "/public/seller_uploads/".$product->seller_id."/reviewImages/".$product -> id;

            Storage::deleteDirectory($defPath);

            Storage::makeDirectory($defPath);

            $images = $request -> file('images');

            $imageColl = "";

            foreach($images as $image){
                $imageName = $image -> getClientOriginalName();
                $imageColl .= $imageName."->seperator<-";
                $imagePath = $defPath."/$imageName";
                Storage::put($imagePath, file_get_contents($image));
            }

            $updatedReview['images'] = $imageColl;
        }
        $review -> update($updatedReview);

        return redirect()->route('orders')->with('success', 'Review updated');
    }

    public function profile(){
        $profile = Auth::user();
        $orders = Orders::with('product')
                         ->where('customer_id', $profile -> id)
                         ->get();
        $moneySpent = 0;
        foreach($orders as $order){
            if($order -> status != 0){
                $moneySpent += $order -> product -> price;
            }
        }
        $profile -> moneySpent = $moneySpent;
        $profile -> orderCount = count($orders);
        $details = CustomerDetails::where('customer_id', $profile -> id)->get();
        if(count($details) == 0){
            $profile -> error = "No address";
            return view('consumer.profile', compact('profile'));
        }
        return view('consumer.profile', compact('profile'));
    }
}
