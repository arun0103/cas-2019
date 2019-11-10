<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Student;
use App\Student_Grade;
use App\Student_Section;
use App\Student_Punch;
use App\Student_Roster;

class StudentDashboardController extends Controller
{
    public function getTotalStudents(){
        $institution_id = Session::get('company_id');
        $allStudents = Student::where('institution_id',$institution_id)->with('grade','section')->get();
        return response()->json($allStudents);
    }
    public function getAbsentStudents(){
        $institution_id = Session::get('company_id');
        $presentStudents = Student_Punch::where([['institution_id',$institution_id],['punch_date',date('Y-m-d')]])->pluck('student_id');
        $absentStudents = Student::where('institution_id',$institution_id)->whereNotIn('student_id',$presentStudents)->with('grade','section')->get();
        return response()->json($absentStudents);
    }
    public function getPresentStudents(){
        $institution_id = Session::get('company_id');
        $presentStudents = Student_Punch::where([['institution_id',$institution_id],['punch_date',date('Y-m-d')]])->with(['student'=> function ($query) {
             $query->with('grade','section');
        }])->get();
        return response()->json($presentStudents);
    }

    // Student Dashboard
    public function getStudentMonthlyLogDetails($month,$year){
        $startDate = $year .'-'.$month.'-01';
        $endDate = $year.'-'.$month.'-31';
        $studentRosters = Student_Roster::where([['institution_id',Session::get('company_id')],['student_id',Session::get('user_id')]])
                                ->whereBetween('date',[$startDate,$endDate])
                                ->with('shift')->get();
        return response()->json($studentRosters);
    }
}
