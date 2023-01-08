<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
   public function admin(){
    $orders = Order::OrderBy('id','DESC')->get();
       return view('backend.index',compact('orders'));
   }
}
