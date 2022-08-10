<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Tithe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TitheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, Tithe $model)
    {

        $query = $model->query();

        $query->when($request->has('s'), function($query) use($request){
            $search = trim($request->s);
            $query->where('date', 'like', '%' . $search . '%')
            ->orWhereHas('member', function($query) use ($search){
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('second_name', 'like', $search . '%')
                    ->orWhere('first_surname', 'like', $search . '%')
                    ->orWhere('second_surname', 'like', $search . '%');
            });

        })
        ->with(['member','user'])
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
            $date=Carbon::now('America/Bogota')->format('Y-m-d');

            foreach($request->tithes as $item){
                $tithe=Tithe::create([
                    'date'=>$date,
                    'user_id'=>$idUser,
                    'value'=>$item['value'],
                    'member_id'=>$item['member']['id'],
                ]);

                Cash::create([
                    'date'=>$date,
                    'user_id'=>$idUser,
                    'value'=>$item['value'],
                    'type'=>'ENTRADA',
                    'respuestable_id'=>$tithe->id,
                    'respuestable_type'=>get_class(new Tithe()),
                ]);
            }

            DB::commit();

            return response()->json(['status' => 'exito', 'msg' => 'Datos guardados'], 200);

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
        $tithe=Tithe::where('id', $id)->with(['member','user'])->first();
        return response()->json($tithe);
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

            $tithe=Tithe::find($request->id);
            $tithe->value=$request->value;
            $tithe->save();

            Cash::where('respuestable_id',$request->id)->where('respuestable_type',get_class(new Tithe()))
            ->update(['value' => $request->value]);

            DB::commit();

            return response()->json($tithe);

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
