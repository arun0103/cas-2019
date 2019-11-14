<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Carbon\Carbon;
use App\Employee;
use App\Punch;
use App\Company;
use App\Student;
use App\Student_Punch;
use App\Student_Roster;
use App\Roster;
use EmployeeController;

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
    public function index(){   
        if(Auth::check()){
            $loggedInUser = Auth::user();
            //dd($loggedInUser);
            session(['user_id' => $loggedInUser->employee_id]);
            session(['company_id' => $loggedInUser->company_id]);
            session(['role' => $loggedInUser->role]);
            session(['user_name' =>$loggedInUser->name]);
            

            switch($loggedInUser->role){
                case 'admin'    :   $company_type = Company::where('company_id',$loggedInUser->company_id)->first()->company_type;
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
                                    }
                                    else if($company_type == 'institute'){
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
                                    break; 
                case 'employee' :   $company_type = Company::where('company_id',$loggedInUser->company_id)->first()->company_type;
                                    session(['company_type' => $company_type]);
                                    if($company_type =='business'){
                                        $now = Carbon::now();
                                        $employeeTotalRosters = Roster::where('employee_id',$loggedInUser->employee_id)->get();//->whereBetween('date',array($now->year.'-'.$now->month.'-01',$now->year.'-'.$now->month.'-31'))->get();
                                        $employeeAbsentRosters = Roster::where([['employee_id',$loggedInUser->employee_id],['final_half_1','AB'],['final_half_2','AB']])->whereDate('date','<=',$now)->get();//->whereBetween('date',array($now->year.'-'.$now->month.'-01',$now->year.'-'.$now->month.'-31'))->get();
                                        $employeePresentRosters = Roster::where([['employee_id',$loggedInUser->employee_id],['final_half_1','PR'],['final_half_2','PR']])->whereDate('date','<=',$now)->get();
                                        if(count($employeeTotalRosters)>0){
                                            $rosterDetails = [
                                                'total'     =>  count($employeeTotalRosters),
                                                'present'   =>  count($employeePresentRosters),
                                                'absent'    =>  count($employeeAbsentRosters),
                                                'late'      =>  0
                                            ];
                                            return view('pages/employee/dashboard',['roster'=>$rosterDetails]);
                                        }
                                        else{
                                            return view('pages/employee/dashboard-no-data');
                                        }
                                    }
                                    break; 
                case 'student'  :   //$company_type = Company::where('company_id',$loggedInUser->institution_id)->first()->company_type;
                                    session(['company_type' => "institute"]);
                                    $totalRosters = Student_Roster::where('student_id',$loggedInUser->employee_id)->get();
                                    $presentRosters = Student_Punch::where([['student_id',$loggedInUser->employee_id],['punch_1','!=',null]])->get();
                                    $absentRosters = Student_Roster::where([['student_id',$loggedInUser->employee_id],['punch_in',null]])->get();
                                    //dd($totalRosters);
                                    if(count($totalRosters)>0){
                                        $rosterDetails = [
                                            'total'     => count($totalRosters),
                                            'present'   => count($presentRosters),
                                            'absent'    => count($totalRosters) - count($presentRosters),
                                            'late'      =>  0
                                        ];
                                        return view('pages/student/dashboard',['roster'=>$rosterDetails]);
                                    }
                                    else{
                                        return view('pages/student/dashboard-no-data');
                                    }
                                    break; 
            }
            return view('home');
        }
        else{
            return view('/login');
        }
    }
}
