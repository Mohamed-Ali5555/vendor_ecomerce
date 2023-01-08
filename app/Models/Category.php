<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['title','slug','summary','photo','is_parent','parent_id','status'];


    //this is related to delete function that used to delete parent with child that related to that.
     public static function shiftChild($cat_id){
      return Category::whereIn('id',$cat_id)->update(['is_parent' =>1]);
} 

public function getChildByParentID($id){
    return Category::where('parent_id',$id)->pluck('title','id'); //show title and id only
}

public function products(){  //this is forign key for products 
    return $this->hasMany('App\Models\Product','cat_id','id')->where('status','active');
}
}