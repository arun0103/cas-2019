<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Session;

class DepartmentController extends Controller
{
    public function getDepartments(){
        $company_id = Session::get('company_id');
        $departments = Department::where('company_id',$company_id)->get();
        return view('pages/admin/department/viewDepartments',['departments'=>$departments]);
    }
    public function addDepartment(Request $request){
        
        $department = Department::create($request->input());
        return response()->json($department);
        // $department = new Department([
        //     'department_id'=>$request->input('department_id'),
        //     'name'=>$request->input('name'),
        //     'company_id'=>$R
        // ]);
        // if($department->save()){
        //     return response()->json($department);
        //     //return redirect('/admin/departments/view')->with('status','Department created!');
        // }
    }
    public function getDepartmentById($id){
        $department = Department::where('department_id',$id)->get();
        return $department[0];
    }
    public function deleteDepartment($id){
        $department = Department::where('department_id',$id)->delete();
        return response()->json($department);
    }
    public function updateDepartment(Request $request, $id){
        $department = Department::where('department_id',$id)->first();
        $department->department_id = $request->department_id;
        $department->name = $request->name;
        $department->save();
        return response()->json($department);
    }
}
