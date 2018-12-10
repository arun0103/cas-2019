<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shift;
use Session;

class ShiftController extends Controller
{
    public function getShifts(){
        $comp_id=Session::get('company_id');
        $shifts = Shift::where('company_id',$comp_id)->get();
        return view('pages/admin/shift/viewShifts', ['shifts'=>$shifts]);
    }
    public function getShiftById($id){
        $get = Shift::where('shift_id',$id)->first();
        return $get;
    }

    public function addShift(Request $request){
        $new = Shift::create($request->input());
        return response()->json($new);
        // $shift = new Shift([
        //     'shift_id'=>request('shift_id'),
        //     'name'=>request('shift_name'),
        //     'start_time'=>request('start_time'),
        //     'end_time'=>request('end_time'),
        //     'grace_late'=>request('late_grace'),
        //     'grace_early'=>request('early_grace'),
        // ]);
        // if($shift->save()){
        //     return redirect('/admin/shift/add')->with('status', "Shift created!");
        // }
    }
    public function updateShift(Request $request, $id){
        $update = Shift::where('shift_id',$id)->first();
        $update->shift_id= $request->shift_id;
        $update->name= $request->name;
        $update->start_time = $request->start_time;
        $update->end_time = $request->end_time;
        $update->grace_early = $request->grace_early;
        $update->grace_late = $request->grace_late;
        $update->save();
        return response()->json($update);
    }
    public function deleteShift($id){
        $delete = Shift::where('shift_id',$id)->delete();
        return response()->json($delete);
    }
    
}
