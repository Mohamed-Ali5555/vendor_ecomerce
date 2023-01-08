<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;

class SellerController extends Controller
{
    public function dashboard(){
        $orders = Order::OrderBy('id','DESC')->get();

        return view('seller.index',compact('orders'));
    }
}
