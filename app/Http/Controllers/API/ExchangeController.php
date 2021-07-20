<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\Exchange;

use App\Jobs\Test;

class ExchangeController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $exchanges = DB::table('exchanges')->get();

        return response()->json($exchanges);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'currency' => 'required|
                            in:AED,AUD,BGN,BRL,CAD,CHF,CNY,CZK,DKK,EGP,EUR,GBP,HRK,HUF,INR,JPY,KRW,MDL,MXN,NOK,NZD,PLN,RSD,RUB,SEK,
                            THB,TRY,UAH,USD,XAU,XDR,ZAR',
            'date' => 'required|date',
            'value' => 'required'
        ]);

        if($validator->fails()){
            return response($validator->messages(), 200);
        } else {
            $exchange = new Exchange;
            $exchange->currency = $request->currency;
            $exchange->date = $request->date;
            $exchange->value = $request->value;
            
            if($request->source) {
                $exchange->source = $request->source;
            }

            $exchange->save();
           
            $this->dispatch(new Test($exchange));

            return response()->json([
                'message'=> 'Inserted Successfuly !'
            ]);
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
        DB::table('exchanges')->where('id', $id)->delete();

        return response()->json([
            'message'=> 'Deleted Successfuly !'
        ]);
    }
}
