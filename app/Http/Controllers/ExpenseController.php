<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Expense $model)
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

            $expense = Expense::create([
                'date' => $request->date,
                'user_id'=>$idUser,
                'value' => $request->value,
                'concept' => $request->concept,
            ]);

            Cash::create([
                'date'=>$request->date,
                'user_id'=>$idUser,
                'value'=>$request->value,
                'type'=>'SALIDA',
                'respuestable_id'=>$expense->id,
                'respuestable_type'=>get_class(new Expense()),
            ]);

            DB::commit();

            return response()->json($expense);

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
        $expense=Expense::where('id', $id)->first();
        return response()->json($expense);
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

            $expense=Expense::find($request->id);
            $expense->date=$request->date;
            $expense->value=$request->value;
            $expense->concept=$request->concept;
            $expense->save();

            Cash::where('respuestable_id',$request->id)->where('respuestable_type',get_class(new Expense()))
            ->update([
                'value' => $request->value,
                'date' => $request->date,
            ]);

            DB::commit();

            return response()->json($expense);

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
