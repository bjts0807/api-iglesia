<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Offering $model)
    {

        $query = $model->query();

        $query->when($request->has('s'), function($query) use($request){
            $search = trim($request->s);
            $query->where('date', 'like', '%' . $search . '%');
            
        })
        ->with(['user'])
        ->orderBy('date','desc');

        return $request->has('per_page')
        ? $query->paginate($request->per_page)
        : $query->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $idUser=Auth::user()->id;

            $offerings = Offering::create([
                'date' => $request->date,
                'user_id'=>$idUser,
                'value' => $request->value,
                'description' => $request->description,
            ]);

            Cash::create([
                'date'=>date('Y-m-d'),
                'user_id'=>$idUser,
                'value'=>$request->value,
                'type'=>'ENTRADA',
                'respuestable_id'=>$offerings->id,
                'respuestable_type'=>get_class(new Offering()),
            ]);

            DB::commit();

            return response()->json($offerings);

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage().PHP_EOL.$ex->getTraceAsString());
            return response()->json(['status' => 'fail', 'msg' => 'Ha ocurrido un error al procesar la solicitud'], 500);
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
        $offering=Offering::where('id', $id)->first();
        return response()->json($offering);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {

            DB::beginTransaction();

            $offering=Offering::find($request->id);
            $offering->date=$request->date;
            $offering->value=$request->value;
            $offering->description=$request->description;
            $offering->save();

            Cash::where('respuestable_id',$request->id)->where('respuestable_type',get_class(new Offering()))
            ->update(['value' => $request->value]);

            DB::commit();

            return response()->json($offering);

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage().PHP_EOL.$ex->getTraceAsString());
            return response()->json(['status' => 'fail', 'msg' => 'Ha ocurrido un error al procesar la solicitud'], 500);
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
        //
    }
}
