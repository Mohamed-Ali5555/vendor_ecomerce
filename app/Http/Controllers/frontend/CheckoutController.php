<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session ;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\Product;

use Illuminate\Support\Facades\Mail ;
use Illuminate\Mail\PendingMail ;
use Gloudemans\Shoppingcart\Facades\Cart;

use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function checkout1(){
        $user=Auth::user();
        // return $user;09
        return view('frontend.pages.checkout.checkout1',compact('user'));
    }

    public function checkout1Store(Request $request){
   
        // return $request->all();
        $this->validate($request,[
            'first_name'=>'string|required',

            'last_name'=>'string|required',
            // 'email'=>'email|required|exist:user,email',
            'phone'=>'string|required',
            'address'=>'string|required',


            'city'=>'string|required',
            'country'=>'string|nullable',
            'state'=>'string|nullable',
            'postcode'=>'string|nullable',
            'note'=>'string|nullable',
            'saddress'=>'string|required',
            'sphone'=>'string|nullable',

            'scity'=>'string|required',
            'scountry'=>'string|nullable',
            'sstate'=>'string|nullable',
            'spostcode'=>'string|nullable',
            // 'sub_total'=>'required|numeric',
            // 'total_amount'=>'required|numeric',
    
    
             ]);
            //  return $request->all();
       Session::put('checkout',[
        'first_name'=>$request->first_name,
        'last_name'=>$request->last_name,
        'email'=>$request->email,
        'phone'=>$request->phone,
        'country'=>$request->country,
        'address'=>$request->address,
        'city'=>$request->city,
        'state'=>$request->state,
        'postcode'=>$request->postcode,
        'note'=>$request->note,




        'sfirst_name'=>$request->sfirst_name,
        'slast_name'=>$request->slast_name,
        'semail'=>$request->semail,
        'sphone'=>$request->sphone,
        'scountry'=>$request->scountry,
        'saddress'=>$request->saddress,
        'scity'=>$request->scity,
        'sstate'=>$request->sstate,
        'spostcode'=>$request->spostcode,

        'sub_total'=>$request->sub_total,
        // 'quantity'=>$request->quantity,
        'total_amount'=>$request->total_amount,


       ]);

    //    there get shipping 
       $shippings = Shipping::where('status','active')->orderBy('shipping_address','ASC')->get();
       return view('frontend.pages.checkout.checkout2',compact('shippings'));

    }

    public function checkout2Store(Request $request){
        // return $request->all();
        $this->validate($request,[
            'delivery_charge'=>'required|numeric'
        ]);
        Session::push('checkout',[  //push get something not in it or from page two search it 
            'delivery_charge'=>$request->delivery_charge,
        ]);
        return view('frontend.pages.checkout.checkout3');
    }

    public function checkout3Store(Request $request){
        // return $request->all();
        $this->validate($request,[
            'payment_method'=>'string|required',
            'payment_status'=>'string|in:paid,unpaid',

        ]);
        Session::push('checkout',[  //push get something not in it or from page two search it 
            'payment_method'=>$request->payment_method,
            'payment_status'=>'unpaid',

        ]);

        // return Session::get('checkout');
        return view('frontend.pages.checkout.checkout4');
    }
    public function checkoutStore(Request $request){

        $order=new Order();
        $order['user_id']=auth()->user()->id;
       
        
        $order['order_number']=Str::upper('ORD-'.Str::random(3));
      
       
        $order['sub_total']=(float)str_replace(',','',Session::get('checkout')['sub_total']);
        if(Session::has('coupon')){
            $order['coupon']=Session::get('coupon')['value'];
        }else{
            $order['coupon']=0;
        }

        // the proplem first 
        // $order['total_amount']=Session::get('checkout')['sub_total']+Session::get('checkout')[0]['delivery_charge']-$order['coupon'];
        //the fixof the problem 
                 $order['total_amount']=(float)str_replace(',','',Session::get('checkout')['sub_total'])+Session::get('checkout')[0]['delivery_charge']-$order['coupon'];

        // $order['delivery_charge']=Session::get('checkout')[0]['delivery_charge'];
        // $order['total_amount']= $order['sub_total'] +  $order['delivery_charge'];
        $order['payment_method']=Session::get('checkout')['1']['payment_method'];
        $order['payment_status']=Session::get('checkout')['1']['payment_status'];
        $order['condition']='pending';
        $order['delivery_charge']=Session::get('checkout')['0']['delivery_charge'];
        $order['first_name']=Session::get('checkout')['first_name'];
        $order['last_name']=Session::get('checkout')['last_name'];
        $order['email']=Session::get('checkout')['email'];
        $order['phone']=Session::get('checkout')['phone'];
        $order['country']=Session::get('checkout')['country'];
        $order['address']=Session::get('checkout')['address'];
        $order['city']=Session::get('checkout')['city'];
        $order['state']=Session::get('checkout')['state'];
        $order['note']=Session::get('checkout')['note'];


        $order['sfirst_name']=Session::get('checkout')['sfirst_name'];
        $order['slast_name']=Session::get('checkout')['slast_name'];
        $order['semail']=Session::get('checkout')['semail'];
        $order['sphone']=Session::get('checkout')['sphone'];
        $order['scountry']=Session::get('checkout')['scountry'];
        $order['saddress']=Session::get('checkout')['saddress'];
        $order['scity']=Session::get('checkout')['scity'];
        $order['sstate']=Session::get('checkout')['sstate'];
        $order['spostcode']=Session::get('checkout')['spostcode'];


        // return $order;
        // PendingMail::to($order['email'])->bcc($order['semail'])->cc('oppnot280@gmail.com')->send($order);
        //  dd('MAIL IS SEND'); // this code after if status before cart::instance

        $status=$order->save();
        foreach(Cart::instance('shopping')->content() as $item){
            $prooduct_id[]=$item->id;
            $product=Product::find($item->id);
            $quantity=$item->qty;
            $order->products()->attach($product,['quantity'=>$quantity]); // this code i dont understant it this related to function in product and order model that belongs to many

        }
        if($status){

            Cart::instance('shopping')->destroy();
            Session::forget('coupon');
            Session::forget('checkout');
            return redirect()->route('complete',$order['order_number']);
        }else{
            return redirect()->route('checkout1')->with('error','please try again');
        }

        // return $order;
//  return Session::get('checkout');

    }

    public function complete($order){
        return view('frontend.pages.checkout.complete',compact('order'));
    }
}
