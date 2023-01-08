<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorys = Category::orderBy('id','DESC')->get();
        return view('backend.category.index',compact(('categorys')));
    }



    public function categoryStatus(Request $request){
        // dd($request->all());
        if($request->mode=='true'){
            DB::table('categories')->where('id',$request->id)->update(['status'=>'active']);
        }else{
            DB::table('categories')->where('id',$request->id)->update(['status'=>'inactive']);

        }

        return response()->json(['msg'=> 'successfuly updated status','status'=>true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_cats = Category::where('is_parent',1)->orderBy('title','ASC')->get();
        return view('backend.category.create',compact('parent_cats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // return $request;
         $validated = $request->validate([
            'title' => 'string|required',
            'summary' => 'string|nullable',
            'photo' => 'required',
            'is_parent' => 'sometimes|in:1',
            'parent_id' => 'nullable',

            'status' => 'nullable|in:active,inactive',
        

    
        ]);
    $data = $request->all();

    $slug = Str::slug($request->input('title'));
    $slug_count = Category::where('slug',$slug)->count();
    if($slug_count>0){
        $slug =time(). '-' .$slug;
    }
    $data['slug']=$slug;
    // $data['is_parent']=$request->input('parent_id', 0);
    $data['is_parent']=$request->input('is_parent',0);
    // return data 
        $new = Category::create($data);
        if($new){
            return redirect()->route('category.index')->with('success',' category successfully created ');

        }else{
             return back()->with('error','something went wrong!');
        }
        // return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $parent_cats = Category::where('is_parent',1)->orderBy('title','ASC')->get();

        if($category){
            return view('backend.category.edit',compact(['category','parent_cats']));
        }else{
            return back()->with('error','Data not found !');
        }
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {

        $category = Category::find($id);
        if($category){


            $validated = $request->validate([
                'title' => 'string|required',
                'summary' => 'string|nullable',
                'is_parent' => 'sometimes|in:1',
                'parent_id' => 'nullable',
                'status' => 'nullable|in:active,inactive',

            ]);
            $data = $request->all();

            if($request->is_parent==1){
                $data['parent_id']= '';
            }

            $data['is_parent']=$request->input('is_parent',0);

            $new = $category->fill($data)->save();

            if($new){
                return redirect()->route('category.index')->with('success',' category successfully updated ');

            }else{
                return back()->with('error','something went wrong!');
            }
    }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $child_cat_id = Category::where('parent_id',$id)->pluck('id');  // parent id == child 
        if($category){
                  $status = $category->delete();
                  if($status){
                      if(count($child_cat_id)>0){
                          Category::shiftChild($child_cat_id);
                      }
                      return redirect()->route('category.index')->with('success','category successfuly deleted');
                  }else{
                      return back()->with('error','something went wrong');
                  }
        }else{
            return back()->with('error','Data not found !');
        }
    }

    public function getChildByParentID(Request $request,$id){
      $category = Category::find($request->id);
      $child_id = Category::getChildByParentID($request->id);

       if($category){
        if(count($child_id)<=0){
            return response()->json(['status'=>false,'data'=>null,'msg'=>'']);
        }
        return response()->json(['status'=>true,'data'=>$child_id,'msg'=>'']);
  
       }else{
        return response()->json(['status'=>false,'data'=>null,'msg'=>'category not found']);

       }
    }
}
