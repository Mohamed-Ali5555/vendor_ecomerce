<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\AbouteUs;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Session ;
use Illuminate\Support\Facades\Redirect ;
use Illuminate\Support\Facades\Auth;
use URL;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function home(){
        
       $banners = Banner::where(['status'=>'active','condition'=>'banner'])->orderBy('id','DESC')->limit('5')->get();
       $promo_banners = Banner::where(['status'=>'active','condition'=>'promo'])->orderBy('id','DESC')->first();

       $categories = Category::where(['status'=>'active','is_parent'=>1])->limit(3)->orderBy('id','DESC')->get();
       
       $new_products = Product::where(['status' => 'active', 'condition' => 'new']) ->orderBy('id', 'DESC')->limit('8') ->get();
       $featured_products = Product::where(['status' => 'active', 'is_featured' =>1]) ->orderBy('id', 'DESC')->limit('6') ->get();
       $brands=Brand::where('status','active')->orderBy('id','DESC')->get();

       //best selling product
       $items = DB::table('product_orders')->select('product_id',DB::raw('COUNT(product_id) as count'))->groupBy('product_id')->orderBy("count",'desc')->get();
    //    return $best_selling;
    $product_ids=[];
      
     foreach($items as $item){
        array_push($product_ids,$item->product_id);
     }
     $idoImloded_selling = implode(',' , array_fill(0,count($product_ids),'?'));
     if($idoImloded_selling !=null){
     $best_sellings=Product::whereIn('id',$product_ids)->orderByRaw("field(id,{$idoImloded_selling})",$product_ids)->get();
    }else{
       $best_sellings=[];
     }
     //Top rated products
     $item_rated=DB::table('product_reviews')->select('product_id',DB::raw('AVG(rate) as count'))->
     groupBy('product_id')->orderBy("count",'desc')->get();
     $product_ids=[];
      
     foreach($item_rated as $item){
        array_push($product_ids,$item->product_id);
     }
    
     $idoImloded = implode(',' , array_fill(0,count($product_ids),'?'));
     if($idoImloded !=null){
     $best_rated=Product::whereIn('id',$product_ids)->orderByRaw("field(id,{$idoImloded})",$product_ids)->get();
     }else{
        $best_rated=[];
    }
    //  return $best_rated;




        return view('frontend.index',
        compact(['banners','categories','new_products','featured_products','promo_banners','brands','best_sellings','best_rated']));
    }


    public function aboutUs(Request $request){
        $brands=Brand::where('status','active')->orderBy('id','DESC')->get();

        $abouts=AbouteUs::first();
        // return $abouts;
        return view('frontend.pages.about_us',compact('abouts','brands'));
    }

    public function contactUs(Request $request){

        // $abouts=AbouteUs::first();
        // return $abouts;
        return view('frontend.pages.contact_us');
    }

    public function contactSubmit(Request $request){
        $this->validate($request,[
            'f_name'=>'string|required',
            'l_name'=>'string|required',
            'email'=>'email|required',
            'subject'=>'min:4|string|required',
            'message'=>'string|nullable|max:200',
        ]);
        $data=$request->all();
        $status=Mail::to('admin@gmail.com')->send(new Contact($data));
        return back()->with('success','successfuly send your enquirey');
    }
    public function productCategoryFilter(Request $request){
 
            return view('frontend.pages.product.product-category',compact('products','cats','brands'));
    
}
    public function shop(Request $request){

      

        $products=Product::query();

        if(!empty($_GET['category'])){
        $slugs=explode(',', $_GET['category']);
        $cat_ids=Category::select('id')->whereIn('slug',$slugs)->pluck('id')->toArray();
        
        $products=$products->whereIn('cat_id', $cat_ids);
        //  dd( $products);
        }
    
            //sort by brand
            
           if(!empty($_GET['brand'])){
            $slugs=explode(',', $_GET['brand']);
            $brand_ids=Brand::select('id')->whereIn('slug',$slugs)->pluck('id')->toArray();
            
            $products=$products->whereIn('brand_id', $brand_ids);
            //  dd( $products);
            }


            //sort by size
           if(!empty($_GET['size'])){            
            $products=$products->where('size', $_GET['size']);
            //  dd( $products);
            }

            //sort by dropdown
            if(!empty($_GET['sortBy'])){
          
            if($_GET['sortBy']=='priceAsc'){
                
                $products =  $products->where(['status'=>'active'])->orderBy('offer_price','Asc');
            }if($_GET['sortBy']=='priceDesc'){
                $products =$products->where(['status'=>'active'])->orderBy('offer_price','Desc');
              
            }if($_GET['sortBy']=='titleAsc'){
                $products = $products->where(['status'=>'active'])->orderBy('title','Asc');
               
            }if($_GET['sortBy']=='titleDesc'){
                $products = $products->where(['status'=>'active'])->orderBy('title','Desc');

            }if($_GET['sortBy']=='disAsc'){
                $products =  $products->where(['status'=>'active'])->orderBy('price','Asc');

            }
            if($_GET['sortBy']=='disDesc'){
                $products =  $products->where(['status'=>'active'])->orderBy('price','Desc');
                

             }
             
            //  dd(($_GET['sortBy']));
            } 

            //sort price or filter price
            if(!empty($_GET['price'])){
                $price=explode('-',$_GET['price']);
                $price[0]=floor($price[0]);
                $price[1]=ceil($price[1]);
                //    dd($price);
                
              $products= $products->whereBetween('offer_price',$price)->where('status','active')->paginate(12);
            //   dd($products);




            }
        
    

        else{
            $products= $products->where('status','active')->paginate(12);
        }

        // Notice........ there the coming line write it in else that behave you i make it commit and this run this problem 
        //  make me serius and i take 2 days in it 
        // $products=Product::where('status','active')->paginate(10);
        $brands=Brand::where('status','active')->orderBy('title','ASC')->with('products')->get();
        $cats=Category::where(['status'=>'active','is_parent'=>1])->with('products')->orderBy('title','ASC')->get();


        return view('frontend.pages.product.shop',compact('products','cats','brands'));
    }


    public function shopFilter(Request $request){
        // dd($request->all());
        $data=$request->all();

        //category fillter 

        // dd($data);
        $catUrl='';
        if(!empty($data['category'])){
            foreach($data['category'] as $category){
                if(empty($catUrl)){
                    $catUrl .='&category='.$category;
                }else{
                   $catUrl .=','.$category; 
                }
            }
        }

        //sort filter 
        $sortByUrl="";
        if(!empty($data['sortBy'])){
            $sortByUrl .='&sortBy=' .$data['sortBy'];
        }
         // return dd($sortByUrl);

        // price filter
        $price_range_url="";
        if(!empty($data['price_range'])){
            $price_range_url .= '&price=' .$data['price_range'];
        }

        //filter brand 
        $brandUrl='';
        if(!empty($data['brand'])){
            foreach($data['brand'] as $brand){
                if(empty($brandUrl)){
                    $brandUrl .='&brand='.$brand;
                }else{
                   $brandUrl .=','.$brand; 
                }
            }
        }


        //size filter
        $sizeUrl="";
        if(!empty($data['size'])){
           $sizeUrl .='&size='.$data['size'];
        }

    //   return dd($price_range_url);
        return redirect()->route('shop',$catUrl.$sortByUrl.$price_range_url.$brandUrl.$sizeUrl);
    }

    //autoseach
    public function autoSearch(Request $request){
        $query=$request->get('term','');
        $products=product::where('title','LIKE','%'.$query.'%')->get();

        $data=array();
        foreach($products as $product){
            $data[]=array('value'=>$product->title,'id'=>$product->id);
        }
        if(count($data)){
            return $data;
        }else{
            return ['value'=>"No Result found",'id'=>''];
        }
    }

    public function search(Request $request){
        $query=$request->input('query');
        $products=product::where('title','LIKE','%'.$query.'%')->orderBy('id','DESC')->paginate(12);
        
        $brands=Brand::where('status','active')->orderBy('title','ASC')->with('products')->get();
        $cats=Category::where(['status'=>'active','is_parent'=>1])->with('products')->orderBy('title','ASC')->get();

        return view('frontend.pages.product.shop',compact('products','cats','brands'));
    } 



 









    public function productCategory(Request $request,$slug){
        $categories=Category::with(['products'])->where('slug',$slug)->first();
        // return $request->all();
        $sort =''; //this is a new variable 
        if($request->sort !=null){
            $sort=$request->sort;
        }
        if($categories==null){
            return view('errors.404');
        }else{
            if($sort=='priceAsc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('offer_price','Asc')->paginate(12);
            }elseif($sort=='priceDesc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('offer_price','Desc')->paginate(12);
              
            }elseif($sort=='titleAsc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('title','Asc')->paginate(12);
               
            }elseif($sort=='titleDesc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('title','Desc')->paginate(12);

            }elseif('disAsc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('price','Asc')->paginate(12);

            }
            elseif('disDesc'){
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->orderBy('price','Desc')->paginate(12);

            }else{
                $products = Product::where(['status'=>'active','cat_id'=>$categories->id])->paginate(12);
            }
        }
        $route = 'product-category';
        ////////////////////////////////////////////
        if($request->ajax()){
            $view = view('frontend/layouts._single-product'.compact('products'))->render();
            return response()->json(['html'=>$view]); 
        }
        ///////////////////////////////////////////
        return view('frontend.pages.product.product-category',compact(['categories','route','products']));
    }

    //product detail

    public function productDetail($slug){
      $product=Product::with('rel_prods')->where('slug',$slug)->first();
      if($product){
          return view('frontend.pages.product.product-detail',compact('product'));
      }else{
          return 'product detail not found';
      }
    }

    //user auth
    public function userAuth(){
        Session::put('url.intended',URL::previous());
        return view('frontend.auth.auth');
    }

    public function loginSubmit(Request $request){
        // return $request->all();
        $this->validate($request,[
            'email'=>'email|required|exists:users,email',
            'password'=>'required|min:4',
        ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password,'status'=>'active'])){
             
            Session::put('user'.$request->email);

            if(Session::get('url.intended')){
                return Redirect::to(Session::get('url.intended'));
            }else{
                return redirect()->route('home')->with('success','Sucessfuly login');
            }
            
            
        }else{
            return back()->with('error','Invalid email & password');
        }
    }

    public function registerSubmit(Request $request){
        // return $request->all();

        $this->validate($request,[
            'username'=>'nullable|string',
            'full_name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'min:4|required|confirmed',
        ]);
        $data=$request->all();
        $check=$this->create($data);
        Session::put('user',$data['email']);
        Auth::login($check);
        if($check){
            return redirect()->route('home')->with('success','Successfuly registered');
        }else{
            return back()->with('error',['Please check your credentials']);
        }
    }

    private function create(array $data){
        return User::create([
            'full_name'=>$data['full_name'],
            'username'=>$data['username'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),

        ]);
    }


    public function userLogout(){
        Session::forget('user');
        Auth::logout();
        return \redirect()->home()->with('success','Successfuly logout');
    }




    //user folder
    public function userAccount(Request $request){
        $validated = $request->validate([
            'newpassword'=>'password|min:4',
            'oldpassword'=>'password|min:4',

            // 'full_name'=>'required|string',
            // 'username'=>'nullable|string',
            // 'phone'=>'nullable|min:8',

        ]);
        $user = Auth::user();
        return view('frontend.user.account',compact('user'));
    }

    

    public function updateAccount(Request $request ,$id){
        // return $request->all();
        $hashpassword=Auth::user()->password;
        // return $hashpassword;
        if($request->oldpassword=null && $request->newpassword==null){
        User::where('id',$id)->update([
            'full_name'=>$request->full_name,
            'username'=>$request->username,
            'phone'=>$request->phone,
        ]);
        return back()->with('success','Account successfuly updated');

    }else{
        if(\Hash::check($request->oldpassword,$hashpassword)){
            if(\Hash::check($request->newpassword,$hashpassword)){
                User::where('id',$id)->update([
                    'full_name'=>$request->full_name,
                    'username'=>$request->username,
                    'phone'=>$request->phone,
                    'password'=>Hash::make($request->newpassword),

                ]);
                return back()->with('success','Account successfuly updated');

            }else{
                return back()->with('error','New password not be the same with old password');
            }

        }else{
            return back()->with('error','New password does not match');
        }
    }
 }



    public function userDashboard(){
        $user = Auth::user();
        return view('frontend.user.dashboard',compact('user'));
    }


    public function userOrder(){
        $user = Auth::user();
        return view('frontend.user.order');
    }

    public function userAddress(){
        $user = Auth::user();
        return view('frontend.user.address',compact('user'));
    }

    public function billingAddress(Request $request,$id){
        $user = user::where('id',$id)->update(['country'=>$request->country,'city'=>$request->city,'postcode'=>$request->postcode,'address'=>$request->address,'state'=>$request->state]);
        if($user){
            return back()->with('success','Address successfuly update');
        } else{
            return back()->with('error','Something went wrong');
        }
    }
        public function shippingAddress(Request $request,$id){
            $user = user::where('id',$id)->update(['scountry'=>$request->scountry,'scity'=>$request->scity,'spostcode'=>$request->spostcode,'saddress'=>$request->saddress,'sstate'=>$request->sstate]);
            if($user){
                return back()->with('success','Shipping Address successfuly update');
            } else{
                return back()->with('error','Something went wrong');
            }
    }

   

}
