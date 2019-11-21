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

    public function getTotalRosterSummary($student_id){
        $student_id_new = explode("%2F", $student_id);
        $real_id="";
        $index = 0;
        if(count($student_id_new)>1){
            foreach($student_id_new as $part){
                $real_id =$real_id.$part;
                if($index != count($student_id_new)-1){
                    $real_id =$real_id."/";
                }
                $index++;
            }
        }else{
            $real_id = $student_id;
        }

        $institution_id = Session::get('company_id');
        $totalRosters = Student_Roster::where('student_id',$real_id)->get();
        $totalClasses = 0;
        $totalHolidays = 0;
        $totalOffs = 0;
        foreach($totalRosters as $roster){
            switch($roster->is_holiday){
                case 'O' : $totalOffs++; break;
                case 'H' : $totalHolidays++; break;
                case 'C' : $totalClasses++; break;
            }
        }
        $totalRosterSummary = [
            'totalRosters' =>count($totalRosters),
            'totalClasses' => $totalClasses,
            'totalHolidays' => $totalHolidays,
            'totalOffs' => $totalOffs
        ];
        return response()->json($totalRosterSummary);
    }

    public function getTotalPresentSummary($student_id){
        
        $institution_id = Session::get('company_id');
        $totalPresent = Student_Punch::where([['institution_id',$institution_id],['student_id',$student_id]])->get();
        
        return  response()->json($student_id);
    }

    public function getTotalAbsentSummary($student_id){
        $institution_id = Session::get('company_id');
        $totalPresent = Student_Punch::where([['institution_id',$institution_id],['student_id',$student_id]])->get();

        $totalAbsent_roster = Student_Roster::where([['student_id',$student_id],['punch_in',null],['is_holiday','!=','H']])->get();
        
        return response()->json($totalAbsent_roster);
    }
}
