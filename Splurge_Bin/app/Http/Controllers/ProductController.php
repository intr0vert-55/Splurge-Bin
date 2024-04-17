<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;

use App\Models\Categories;

use App\Http\Requests\ProductRequest;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Models\WishListBin;

class ProductController extends Controller
{


    public function add(){
        $categories = Categories::all();
        return view('seller.productadd',compact('categories'));
    }

    public function store(ProductRequest $request){
        $product = [
            'name' => $request -> name,
            'info' => $request -> info,
            'price' => $request -> price,
            'category_id' => $request -> category,
            'seller_id' => Auth::user()->id,
        ];

        // $id = Products::latest('created_at')->first();
        // $id = $id -> id + 1;
        // dd($id);

        $product["image"] = "dummy";


        $product = Products::create($product);


        $defPath = "/public/seller_uploads/".Auth::user()->id."/".$product -> id;

        Storage::makeDirectory($defPath);

        $images = $request -> file('images');

        $imageColl = "";

        foreach($images as $image){
            $imageName = $image -> getClientOriginalName();
            $imageColl .= $imageName."->seperator<-";
            $imagePath = $defPath."/$imageName";
            Storage::put($imagePath, file_get_contents($image));
        }

        $product -> image = $imageColl;

        $product -> update();

        // dd($product);


        return redirect()->route('home')->with('success', 'Product Added ✓');
    }

    public function edit($id){
        $product = Products::find($id);
        $categories = Categories::all();
        return view('seller.productadd', compact('product', 'categories'));
    }

    public function update($id, ProductRequest $request){
        $product = Products::find($id);
        $updatedProduct = [
            'name' => $request -> name,
            'info' => $request -> info,
            'price' => $request -> price,
            'category' => $request -> category,
        ];

        $updatedProduct['image'] = $product -> image;

        if($request -> hasFile('images')){
            $imageColl = "";
            $images = $request -> file('images');
            $deleteFolder = "/public/seller_uploads/".Auth::user()->id."/".$product->id;
            Storage::deleteDirectory($deleteFolder);
            $defPath = "/public/seller_uploads/".Auth::user()->id."/".$product->id;
            Storage::makeDirectory($defPath);
            foreach($images as $image){
                $imageName = $image->getClientOriginalName();
                $imageColl .= $imageName."->seperator<-";
                $imagePath = $defPath."/$imageName";
                Storage::put($imagePath, file_get_contents($image));
            }
            $updatedProduct['image'] = $imageColl;
        }

        $product -> update($updatedProduct);
        return redirect()->route('home')->with('success', 'Product Updated ✓');
    }

    public function delete(Request $request){
        try{
            $product = Products::findOrFail($request -> id);
            $deleteFolder = "/public/seller_uploads/".Auth::user()->id."/".$product->id;
            Storage::deleteDirectory($deleteFolder);
            $product -> delete();
            $response = response()->json(['result' => true]);
        } catch(Exception $e){
            $response = response()->json(['result' => false], 404);
        }
        return $response;
    }

    public function view($id){
        $product = Products::find($id);
        if($product == null)    return redirect()->route()->with('error', "Product doesn't exist");
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
        // dd($product);
        return view('seller.product', compact('product'));
    }

    public function addToWishList(Request $request){
        $product_id = $request -> id;
        $customer_id = Auth::user() -> id;
        $product = Products::find($product_id);
        if($product == null){
            return response()->json(['result' => false, 'message' => 'Product not found.'], 404);
        }
        $inwishList = WishListBin::where('product_id', $product_id)
                                ->where('customer_id', $customer_id)
                                ->first();
        // dd($inwishList);
        if($inwishList){
            $wishlistbin = [
                'product_id' => $inwishList -> product_id,
                'customer_id' => $inwishList -> customer_id,
                'wishlist' => 1,
                'bin' => $inwishList -> bin,
            ];
            $inwishList->update($wishlistbin);
        } else{
            $wishlistbin = [
                'product_id' => $product_id,
                'customer_id' => $customer_id,
                'wishlist' => 1,
                'bin' => 0,
            ];
            WishListBin::create($wishlistbin);
        }
        return response()->json(['result' => true, 'message' => 'Product added to wishlist  ✔']);
    }

    public function removeFromWishList(Request $request){
        $product_id = $request -> id;
        $customer_id = Auth::user() -> id;
        $product = Products::find($product_id);
        if($product == null){
            return response()->json(['result' => false, 'message' => 'Product not found.'], 404);
        }
        $inwishList = WishListBin::where('product_id', $product_id)
                                ->where('customer_id', $customer_id)
                                ->first();
        // dd($inwishList);
        if($inwishList -> bin == 0){
            $inwishList -> delete();
        } else{
            $wishlistbin = [
                'product_id' => $inwishList -> product_id,
                'customer_id' => $inwishList -> customer_id,
                'wishlist' => 0,
                'bin' => $inwishList -> bin,
            ];
            $inwishList->update($wishlistbin);
        }
        return response()->json(['result' => true, 'message' => 'Product removed from wishlist  ✔']);
    }

    public function addToBin(Request $request){
        $product_id = $request -> id;
        $customer_id = Auth::user() -> id;
        $product = Products::find($product_id);
        if($product == null){
            return response()->json(['result' => false, 'message' => 'Product not found.'], 404);
        }
        $inwishList = WishListBin::where('product_id', $product_id)
                                ->where('customer_id', $customer_id)
                                ->first();
        // dd($inwishList);
        if($inwishList){
            $wishlistbin = [
                'product_id' => $inwishList -> product_id,
                'customer_id' => $inwishList -> customer_id,
                'wishlist' => $inwishList -> wishlist,
                'bin' => 1,
            ];
            $inwishList->update($wishlistbin);
        } else{
            $wishlistbin = [
                'product_id' => $product_id,
                'customer_id' => $customer_id,
                'wishlist' => 0,
                'bin' => 1,
            ];
            WishListBin::create($wishlistbin);
        }
        return response()->json(['result' => true, 'message' => 'Product added to bin  ✔']);
    }

    public function removeFromBin(Request $request){
        $product_id = $request -> id;
        $customer_id = Auth::user() -> id;
        $product = Products::find($product_id);
        if($product == null){
            return response()->json(['result' => false, 'message' => 'Product not found.'], 404);
        }
        $inwishList = WishListBin::where('product_id', $product_id)
                                ->where('customer_id', $customer_id)
                                ->first();
        // dd($inwishList);
        if($inwishList -> wishlist == 0){
            $inwishList -> delete();
        } else{
            $wishlistbin = [
                'product_id' => $inwishList -> product_id,
                'customer_id' => $inwishList -> customer_id,
                'wishlist' => $inwishList -> wishlist,
                'bin' => 0,
            ];
            $inwishList->update($wishlistbin);
        }
        return response()->json(['result' => true, 'message' => 'Product removed from bin  ✔']);
    }
}
