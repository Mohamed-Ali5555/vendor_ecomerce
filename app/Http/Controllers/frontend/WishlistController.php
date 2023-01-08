<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;

class WishlistController extends Controller
{
    public function wishlist(){
        return view('frontend.pages.wishlist');
    }

    public function wishlistStore(Request $request){

        $product_id = $request->input('product_id');
        $product_qty = $request->input('product_qty');
       

        $product=Product::getProductByCart($product_id);
        //    dd ($product);
        $price = $product[0]['offer_price'];
        // dd($price) ;

        $wishlist_array=[];
        foreach(Cart::instance('wishlist')->content() as $item){
            $wishlist_array[]=$item->id;
        }
        // this code refer to when you add the same product in wishlist page not add to it 
        if(in_array($product_id,$wishlist_array)){
            $response['percent']=true;
            $response['percent']="Item is aleady in your wishlist";
        }else{

        
        $result=Cart::instance('wishlist')->add($product_id,$product[0]['title'],$product_qty,$price)->associate('App\Models\Product');

        if($result){
            $response['status']=true;
            // $response['product_id']=$product_id;
            // $response['total']=Cart::subtotal();
            $response['wishlist_count']=Cart::instance('wishlist')->count();
            $response['message']="It was add to wishlist poage";

        }
            }
   
          //header refresh when you add product
    // if($request->ajax()){
    //     $header=view('frontend.layouts.header')->render();
    //     $response['header']=$header;
    // }
    return json_encode($response);
           
    }

    public function moveToCart(Request $request){
      // $item=$request->input('rowId');

      $item=Cart::instance('wishlist')->get($request->input('rowId'));
      // $item = Cart::instance('instance')->content()->where('rowId', $rowId)->first();
    //  $item= Cart::instance('wishlist')->add($request->input('rowId'));

      // $item=Cart::get($rowId);
    // return $item;

      Cart::instance('wishlist')->remove($request->input('rowId'));

      $result=Cart::instance('shopping')->add($item->id,$item->name,1,$item->price)->associate('App\Models\Product');

      if($result){
        $response['status']=true;
        $response['message']="item has moved to cart";
        $response['cart_count']=Cart::instance('shopping')->count();
      }
      if($request->ajax()){
        $wishlist=view('frontend.layouts._wishlist')->render();
        $response['wishlist_list']=$wishlist;

        $header=view('frontend.layouts.header')->render();
        $response['header']=$header;
      }

      return $response;
    }


    public function wishlistDelete(Request $request){
        $id=$request->input('rowId');
        Cart::instance('wishlist')->remove($id);


        $response['status']=true;
        $response['message']="item has removed from wishlist";
        $response['cart_count']=Cart::instance('shopping')->count();


        if($request->ajax()){
            $wishlist=view('frontend.layouts._wishlist')->render();
            $header=view('frontend.layouts.header')->render();

            $response['wishlist_list']=$wishlist;
            $response['header']=$header;
            $wishlist=view('frontend.layouts.header')->render();
          
          }
          
          return $response;
    }
}
