<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Member $model)
    {

        $query = $model->query();

        $query->when($request->has('s'), function($query) use($request){
            $search = trim($request->s);
            $query->where('first_name', 'like', '%' . $search . '%');
            
        });
        // ->with(['factura.usuario','user']);

        return $request->has('per_page')
        ? $query->paginate($request->per_page)
        : $query->get();
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

            $members = Member::create([
                'first_name' => strtoupper($request->first_name),
                'second_name' => strtoupper($request->second_name),
                'first_surname' => strtoupper($request->first_surname),
                'second_surname' => strtoupper($request->second_surname),
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'phone' => $request->phone,
                'address' => strtoupper($request->address),
                'email' => $request->email,
                'birthday' => $request->birthday,
                'active_member' => $request->active_member,
            ]);

            DB::commit();

            return response()->json($members);

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
}
