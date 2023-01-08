<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;

use Gloudemans\Shoppingcart\Facades\Cart;
class CartController extends Controller
{
    public function cart(){
        return view('frontend.pages.cart.index');
    }

    //////////////////////////////////////////////////
    public function cartStore(Request $request){
       $product_qty=$request->input('product_qty');
       $product_id=$request->input('product_id');
       $product=Product::getProductByCart($product_id);
    //    return $product;
    $price=$product[0]['offer_price'];
    
    // dd($price);

    $cart_array=[];
    foreach(Cart::instance('shopping')->content() as $item){
        $cart_array[]=$item->id;
    }

    $result=Cart::instance('shopping')->add($product_id,$product[0]['title'],
    $product_qty,$price)->associate('App\Models\Product');
    
     
    if($result){
        $response['status']=true;
        $response['product_id']=$product_id;
        $response['total']=Cart::subtotal();
        $response['cart_count']=Cart::instance('shopping')->count();
        $response['message']="It was added";

    }
    //header refresh when you add product
    if($request->ajax()){
        $header=view('frontend.layouts.header')->render();
        $response['header']=$header;
    }
    return json_encode($response);
}

public function cartDelete(Request $request){
    // dd($request->all());
    $id=$request->input('cart_id');
    Cart::instance('shopping')->remove($id);

        $response['status']=true;
        $response['message']="cart successfuly removed";
        $response['total']=Cart::subtotal();
        $response['cart_count']=Cart::instance('shopping')->count();
    
    if($request->ajax()){
        $header=view('frontend.layouts.header')->render();
        $response['header']=$header;
    }
    return json_encode($response);
}



public function cartUpdate(Request $request){
    $this->validate($request,[
      'product_qty'=>'required|numeric',
    ]);
    $rowId=$request->input('rowId');
    $request_quantity=$request->input('product_qty');
    $productQuantity=$request->input('productQuantity');



    if($request_quantity>$productQuantity){
        $message="we currently do not have enough items in stock";
        $response['status']=false;
    }elseif($request_quantity<1){
        $message="you can not add less than 1 quantity";
        $response['status']=false;

    }
    else{
        Cart::instance('shopping')->update($rowId,$request_quantity);
        $message="Quantity was updated successfully";
        $response['status']=true;
        $response['total']=Cart::subtotal();
        $response['cart_count']=Cart::instance('shopping')->count();
    }
    $response['cart_count']=Cart::instance('shopping')->count();

    return $response;
}
//      COUPON
public function couponAdd(Request $request){
//    return $request->all();

    $code=$request->input('code');

   $coupon=Coupon::where('code',$code)->first();
  //return $coupon;// test it
   
   if(!$coupon){   //if it not equel code on databaise =>backend make error
    return back()->with('error','Invalid coupon code , please enter valid coupon code');
   }
   if($coupon){
    $total_price = (float)str_replace(',','', Cart::instance('shopping')->subtotal());
    // return $total_price;
    session()->put('coupon',[
        'id'=>$request->id,
        'code'=>$request->code,
        'value'=>$coupon->discount($total_price),
    ]);
    return back()->with('success','Coupon Applied successfuly');
    
   }

}
}
