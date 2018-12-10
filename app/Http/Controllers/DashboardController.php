<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use DateTime;

use App\Company;
use App\Employee;
use App\Punch;
use App\Student;
use App\Student_Punch;
use App\Shift;

class DashboardController extends Controller
{
    // Function to send refreshed contents of dashboard of company type = insititute
    public function getNewDashboardContents_institute(){
        $institution_id = Session::get('company_id');
        $totalEmployees = Employee::where('company_id',$institution_id)->count();
        $presentEmployees = Punch::where([['company_id',$institution_id],['punch_date',Carbon::today()]])->get();
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
        
        $totalStudents = Student::where('institution_id',$institution_id)->count();
        $presentStudents = Student_Punch::where([['institution_id',$institution_id],['punch_date',Carbon::today()]])->get();
        $lateStudents = 0;
        $studentDetails = [
            'total'=>$totalStudents,
            'present'=>count($presentStudents),
            'late'=>$lateStudents
        ];
        $dataToSend = [
            'employee'=>$employeeDetails,
            'student'=>$studentDetails
        ];

        return response()->json($dataToSend);
    }
    // Function to send refreshed contents of dashboard of company type = business
    public function getNewDashboardContents_business(){
        $company_id = Session::get('company_id');
        $totalEmployees = Employee::where('company_id',$company_id)->count();
        $presentEmployees = Punch::where([['company_id',$company_id],['punch_date',Carbon::today()]])->get();
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
        return response()->json($employeeDetails);
    }

    public function getTotalEmployees(){
        $company_id = Session::get('company_id');
        $allEmployees = Employee::where('company_id',$company_id)->with('branch','department','designation')->with(['punch_records'=>function($query){
           $query->where('punch_date',date('Y-m-d'))->first(); 
        }])->get();
        return response()->json($allEmployees);
    }
    public function getAbsentEmployees(){
        $company_id = Session::get('company_id');
        $presentEmployees = Punch::where([['company_id',$company_id],['punch_date',date('Y-m-d')]])->pluck('emp_id');
        $absentEmployees = Employee::where('company_id',$company_id)->whereNotIn('employee_id',$presentEmployees)->with('branch','department','designation')->with(['appliedLeaves'=>function($query){
           $query->where([['leave_from','<=',date('Y-m-d')],['leave_to','>=',date('Y-m-d')]])->get();
        }])->get();
        return response()->json($absentEmployees);
    }
    public function getPresentEmployees(){
        $company_id = Session::get('company_id');
        $presentEmployees = Punch::where([['company_id',$company_id],['punch_date',date('Y-m-d')]])->with(['employee'=>function($query){
            $query->with('branch','department','designation');
        }])->get();
        return response()->json($presentEmployees);
    }

    public function getLateEmployees(){
        $company_id = Session::get('company_id');
        $lateEmployees = Punch::where([['company_id',$company_id],['punch_date',date('Y-m-d')],['late_in','>',0]])->with(['employee'=>function($query){
            $query->with('branch','department','designation');
        }])->get();
        return $lateEmployees;
    }

    public function makeDateTime($date, $time){
        $timeWithoutMeridian = substr($time,0,-3);
        $timeToConcat = substr($time,0,-3);
        $meridian = substr($time,-2);
        $hr = substr($time,0,2);
        
        if($meridian =="AM"){
            if($hr == "12")
                $timeToConcat = "00".substr($timeWithoutMeridian,2);
        }else{
            if($hr !="12")
                $hr = intVal($hr)+12;
            $timeToConcat = $hr.substr($timeWithoutMeridian,2);
        }
        return $date." ".$timeToConcat.":00";
    }
}
