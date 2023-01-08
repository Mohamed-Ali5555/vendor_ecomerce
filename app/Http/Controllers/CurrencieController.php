<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currencie;
use Illuminate\Support\Facades\DB;

class CurrencieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function currencyLoad(Request $request){
        // dd( $request->all());
        session()->put('currency_code',$request->currency_code);
     
        $currency=Currencie::where('code',$request->currency_code)->first();
        session()->put('currency_symbol',$currency->symbol);
        session()->put('currency_exchange_rate',$currency->exchange_rate);

        $response['status']=true;
        return $response;

    }
    public function index()
    {
        $currencys = Currencie::orderBy('id','DESC')->get();
        return view('backend.currency.index',compact(('currencys')));
    }


    public function currencyStatus(Request $request){
        if($request->mode=='true'){
            DB::table('currencies')->where('id',$request->id)->update(['status'=>'active']);

        }else{
            DB::table('currencies')->where('id',$request->id)->update(['status'=>'inactive']);

        }
        return response()->json(['msg'=>'successfuly updated status' , 'status'=>true]);
    }


    public function create()
    {
        return view('backend.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(  $request,[
            'name'=>'string|required',
            'symbol'=>'string|required',
            'exchange_rate'=>'numeric|required',
            'code'=>'string|required',

            'status'=>'nullable|in:active,inactive',

        ]);
        $data=$request->all();
        $new=Currencie::create($data);

   
        if($new){
            return redirect()->route('currency.index')->with('success','currency successfuly created');
        }else{
            return back()->with('error','something went wrong!');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $currency = Currencie::find($id);
        if($currency){
            return view('backend.currency.edit',compact('currency'));
        }else{
            return back()->with('error','Data not found !');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currency = Currencie::find($id);
        if($currency){


            $this->validate(  $request,[
                'name'=>'string|required',
                'symbol'=>'string|required',
                'exchange_rate'=>'numeric|required',
                'code'=>'string|required',
    
                'status'=>'nullable|in:active,inactive',
    
            ]);
    $data = $request->all();
    $new = $currency->fill($data)->save();
    if($new){
        return redirect()->route('currency.index')->with('success','successfully updated currency');

    }else{
         return back()->with('error','something went wrong!');
    }



        }else{
            return back()->with('error','Data not found !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $currency = Currencie::find($id);
        if($currency){
                  $status = $currency->delete();
                  if($status){
                      return redirect()->route('currency.index')->with('success','currency successfuly deleted');
                  }else{
                      return back()->with('error','something went wrong');
                  }
        }else{
            return back()->with('error','Data not found !');
        }
    }
}
