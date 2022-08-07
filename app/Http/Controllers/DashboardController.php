<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Offering;
use App\Models\Tithe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */


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

    public function dashboardCount(){

        $date_now=Carbon::now('America/Bogota')->format('Y-m-d');

        //members
        $total_members_active = Member::where('active_member','S')->count();
        $total_members = Member::count();

        //incomes
        $total_incomes = Cash::where('type', 'ENTRADA')
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_incomes_day = Cash::where('type', 'ENTRADA')
        ->where('date', $date_now)
        ->select(DB::raw("sum(value) as total"))
        ->first();

        //expenses
        $total_expenses = Cash::where('type', 'SALIDA')
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_expenses_day = Cash::where('type', 'SALIDA')
        ->where('date', $date_now)
        ->select(DB::raw("sum(value) as total"))
        ->first();

        $total_cash=(($total_incomes->total)-($total_expenses->total));

        $month = Carbon::now()->month;
        $total_incomes_month = Cash::where('type', 'ENTRADA')
        ->whereMonth('date',$month)
        ->select(DB::raw("sum(value) as total"),'date')
        ->groupBy('date')
        ->get();

        $total_expenses_month = Cash::where('type', 'SALIDA')
        ->whereMonth('date',$month)
        ->select(DB::raw("sum(value) as total"),'date')
        ->groupBy('date')
        ->get();

        $total_expenses_and_incomes =Cash::whereMonth('date',$month)
        ->select(DB::raw("sum(value) as total"),'type')
        ->groupBy('type')
        ->get();

        $results=[
            "total_incomes"=>$total_incomes->total,
            "total_incomes_day"=>$total_incomes_day->total,
            "total_expenses"=>$total_expenses->total,
            "total_expenses_day"=>$total_expenses_day->total,
            "total_cash"=>$total_cash,
            "total_members"=>$total_members,
            "total_members_active"=>$total_members_active,
            "total_incomes_month"=>$total_incomes_month,
            "total_expenses_month"=>$total_expenses_month,
            "total_expenses_and_incomes"=>$total_expenses_and_incomes,
        ];

        return response()->json($results);
    }
}
