<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\InstituteShift;

class InstituteController extends Controller
{
    public function getInstituteShifts(){
        $institution_id = Session::get('company_id');
        $shifts = InstituteShift::where('institution_id',$institution_id)->get();
        return view('pages/admin/student/viewShift',['shifts'=>$shifts]);
    }

    public function getInstitutionShiftById($id){
        $institution_id = Session::get('company_id');
        $get = InstituteShift::where([['institution_id',$institution_id],['id',$id]])->first();
        return $get;
    }

    public function addInstitutionShift(Request $req){
        $new = InstituteShift::create($req->input());
        return $new;
    }
    
    public function updateInstitutionShift($id, Request $req){
        $institution_id = Session::get('company_id');
        $update = InstituteShift::where([['institution_id',$institution_id],['id',$id]])->first();
        $update->name= $req->name;
        $update->start_time = $req->start_time;
        $update->end_time = $req->end_time;
        $update->weekly_off = $req->weekly_off;
        if($update->save())
            return response()->json($update);
    }

    public function deleteInstitutionShift($id){
        $institution_id = Session::get('company_id');
        $delete = InstituteShift::where([['institution_id',$institution_id],['id',$id]])->delete();
        return response()->json($delete);

    }
}
