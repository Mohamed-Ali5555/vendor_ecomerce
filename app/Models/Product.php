<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Product extends Model
{
    use HasFactory;
    protected $fillable=['title','slug','summary','description','additional_info',
    'return_cancellation','stock','price','offer_price','discount','condition','is_featured',
    'status','photo','size_guide','user_id','added_by','brand_id','cat_id','child_cat_id','size'];

   public function brand(){
       return $this->belongsTo('App\Models\Brand');
   }

   
   public function category(){
    return $this->belongsTo('App\Models\Category','cat_id','id');
}
   
public function product_reviews(){
    return $this->belongsTo('App\Models\ProductReview','product_id','id');
}
   //related product

   public function rel_prods(){
       return $this->hasMany('App\Models\Product','cat_id','cat_id')->where('status','active')->limit(10);

   }
   public static function getProductByCart($id){
       return self::where('id',$id)->get()->toArray();
   }

   public function orders(){
    return $this->belongsToMany(Order::class,'product_orders')->withPivot('quantity');
}
}
