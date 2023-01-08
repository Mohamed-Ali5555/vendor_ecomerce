<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;

class CompareControler extends Controller
{
    public function compare(){
        return view('frontend.pages.compare');
    }

    public function compareStore(Request $request){

        $product_id = $request->input('product_id');
       
        $product=Product::getProductByCart($product_id);
        //    dd ($product);
        $price = $product[0]['offer_price'];
        // dd($price) ;

        $compare_array=[];
        foreach(Cart::instance('compare')->content() as $item){
            $compare_array[]=$item->id;
        }
        // this code refer to when you add the same product in wishlist page not add to it 
        if(in_array($product_id,$compare_array)){
            $response['percent']=true;
            $response['percent']="Item is aleady in your compare";
        }elseif(count($compare_array)>5){
            $response['status']=false;
            $response['message']="we dont have enough items";
        }elseif($product[0]['stock']<=0){
            $response['status']=false;
            $response['message']="we dont have enough items";
        }
        else{

        
        $result=Cart::instance('compare')->add($product_id,$product[0]['title'],1,$price)->associate('App\Models\Product');

        if($result){
            $response['status']=true;
            // $response['product_id']=$product_id;
            // $response['total']=Cart::subtotal();
            $response['compare_count']=Cart::instance('compare')->count();
            $response['message']="It was add to compare poage";

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

      $item=Cart::instance('compare')->get($request->input('rowId'));
      // $item = Cart::instance('instance')->content()->where('rowId', $rowId)->first();
    

      Cart::instance('compare')->remove($request->input('rowId'));

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


    
    public function moveToWishlist(Request $request){
        // $item=$request->input('rowId');
  
        $item=Cart::instance('compare')->get($request->input('rowId'));
        // $item = Cart::instance('instance')->content()->where('rowId', $rowId)->first();
      
  
        Cart::instance('compare')->remove($request->input('rowId'));
  
        $result=Cart::instance('wishlist')->add($item->id,$item->name,1,$item->price)->associate('App\Models\Product');
  
        if($result){
          $response['status']=true;
          $response['message']="item has moved to cart";
        }
        if($request->ajax()){
          $wishlist=view('frontend.layouts._wishlist')->render();
          $compare=view('frontend.layouts._compare')->render();

          $response['wishlist_list']=$wishlist;
          $response['compare_list']=$compare;

          $header=view('frontend.layouts.header')->render();
          $response['header']=$header;
        }
  
        return $response;
      }

    public function compareDelete(Request $request){
        $id=$request->input('rowId');
        Cart::instance('compare')->remove($id);


        $response['status']=true;
        $response['message']="item has removed from compare";
        $response['cart_count']=Cart::instance('shopping')->count();


        if($request->ajax()){
            $wishlist=view('frontend.layouts._wishlist')->render();
            $compare=view('frontend.layouts._compare')->render();

            $header=view('frontend.layouts.header')->render();

            $response['wishlist_list']=$wishlist;
            $response['compare_list']=$compare;

            $response['header']=$header;
            $wishlist=view('frontend.layouts.header')->render();
          
          }
          
          return $response;
    }
}
