<?php

namespace App\Http\Controllers;
use App\Models\AbouteUs;

use Illuminate\Http\Request;

class AboutusController extends Controller
{
    public function index(){
        $about=AbouteUs::first();
        return view('backend.aboutus.index',compact('about'));
    }


    public function aboutUpdate(Request $request){
        // return $request->all();
 

        $aboutus=AbouteUs::first();
        $status=$aboutus->update([
            'heading'=>$request->heading,
            'content'=>$request->content,
            'image'=>$request->image,
            'experience'=>$request->experience,
            'happy_customer'=>$request->happy_customer,
            'team_advisor'=>$request->team_advisor,
            'return_customer'=>$request->return_customer,
 
        ]);
        if($status){
            return back()->with('success','AbouteUs updated successfuly');
        }else{
            return back()->with('error','Something went wrong');
        }
    }
}
