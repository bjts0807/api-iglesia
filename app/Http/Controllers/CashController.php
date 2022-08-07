<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Expense;
use App\Models\Offering;
use App\Models\Tithe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
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
        //
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

    public function getCash(){
        $total_incomes = Cash::where('type', 'ENTRADA')
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_expenses_cash = Cash::where('type', 'SALIDA')
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $date_now=Carbon::now('America/Bogota')->format('Y-m-d');

        $total_tithes = Tithe::where('date', $date_now)
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_offerings = Offering::where('date', $date_now)
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_expenses = Expense::where('date', $date_now)
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_cash=(($total_incomes->total)-($total_expenses_cash->total));

        $results=[
            "total_incomes"=>$total_incomes->total,
            "total_expenses_cash"=>$total_expenses_cash->total,
            "total_cash"=>$total_cash,
            "total_tithes"=>$total_tithes->total,
            "total_offerings"=>$total_offerings->total,
            "total_expenses"=>$total_expenses->total,
        ];

        return response()->json($results);

    }
}
