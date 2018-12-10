<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Designation;

class DesignationController extends Controller
{
    public function getDesignations(){
        $comp_id = Session::get('company_id');
        $designations = Designation::where('company_id',$comp_id)->get();
        return view('pages/admin/designation/viewDesignations',['designations'=>$designations]);
    }
    public function getDesignationById($id){
        $comp_id = Session::get('company_id');
        $result = Designation::where([['company_id',$comp_id],['designation_id',$id]])->first();
        return $result;
    }
    public function addDesignation(Request $request){
        $new = Designation::create($request->input());
        return response()->json($new);
        // $designation = new Designation([
        //     'designation_id'=>request('designation_id'),
        //     'name'=>request('designation_name')
        // ]);
        // if($designation->save()){
        //     return redirect('/admin/designation/add')->with('status','Designation created!');
        // }
    }
    public function updateDesignation(Request $request, $id){
        $update = Designation::where('designation_id',$id)->first();
        $update->designation_id = $request->designation_id;
        $update->name = $request->name;
        $update->save();
        return response()->json($update);
    }
    public function deleteDesignation($id){
        $comp_id = Session::get('company_id');
        $delete = Designation::where([['company_id',$comp_id],['designation_id',$id]])->delete();
        return response()->json($delete);
    }
}
