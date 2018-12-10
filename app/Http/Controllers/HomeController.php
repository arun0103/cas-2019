<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Employee;
use App\Punch;
use App\Company;
use App\Student;
use App\Student_Punch;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   if(Auth::check()){
            $loggedInUser = Auth::user();
            session(['user_id' => $loggedInUser->id]);
            session(['company_id' => $loggedInUser->company_id]);
            session(['role' => $loggedInUser->role]);
            session(['user_name' =>$loggedInUser->name]);

            if($loggedInUser->role =='admin'){
                $company_type = Company::where('company_id',$loggedInUser->company_id)->first()->company_type;
                session(['company_type' => $company_type]);
                if($company_type =='business'){
                    $totalEmployees = Employee::where('company_id',$loggedInUser->company_id)->count();
                    $presentEmployees = Punch::where([['company_id',$loggedInUser->company_id],['punch_date',Carbon::today()]])->get();
                    $lateEmployees = 0;
                    if(count($presentEmployees)>0){
                        foreach($presentEmployees as $emp){
                            if($emp->late_in >0 && $emp->late_in != null){
                                $lateEmployees++;
                            }
                        }
                    }    
                    return view('pages/admin/dashboard-business',['total'=>$totalEmployees, 'present'=>count($presentEmployees), 'late'=>$lateEmployees]);
                }else if($company_type == 'institute'){
                    $totalEmployees = Employee::where('company_id',$loggedInUser->company_id)->count();
                    $presentEmployees = Punch::where([['company_id',$loggedInUser->company_id],['punch_date',Carbon::today()]])->get();
                    $lateEmployees = 0;
                    if(count($presentEmployees)>0){
                        foreach($presentEmployees as $emp){
                            if($emp->late_in >0 && $emp->late_in != null){
                                $lateEmployees++;
                            }
                        }
                    }
                    $employeeDetails = [
                        'total'=>$totalEmployees, 
                        'present'=>count($presentEmployees), 
                        'late'=>$lateEmployees
                    ];
                    
                    $totalStudents = Student::where('institution_id',$loggedInUser->company_id)->count();
                    $presentStudents = Student_Punch::where([['institution_id',$loggedInUser->company_id],['punch_date',Carbon::today()]])->get();
                    $lateStudents = 0;
                    $studentDetails = [
                        'total'=>$totalStudents,
                        'present'=>count($presentStudents),
                        'late'=>$lateStudents
                    ];
                    return view('pages/admin/dashboard-institute',['employeeDetails'=>$employeeDetails, 'studentDetails'=>$studentDetails]);
                }
                
            }
            return view('home');
        }
        else{
            return view('/login');
        }
    }
}
