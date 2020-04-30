<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use PDF;
use PdfReport;
use ExcelReport;
use Carbon\Carbon;

use App\Student;
use App\Student_Grade;
use App\Student_Section;
use App\Student_Roster;
use App\Student_Punch;


class StudentReportController extends Controller
{
    public function getSectionsOfGrade($grade_id){
        $company_id = Session::get('company_id');
        //dd($company_id);
        $sections = Student_Section::where([['institution_id',$company_id],['grade_id',$grade_id]])->get(['section_id','name']);
        return response()->json($sections);
    }
    public function generateReportStudent(Request $req){
        $comp_id = Session::get('company_id');
        $reportType = $req->selectedReportType;
        
        $fromDate = $req->fromDate;
        $this->fromDate = $fromDate;
        $toDate = $req ->toDate;
        $grades = $req->selectedGrades;
        $get_report_type = $req->generate_type;
        if($reportType != "rep_total_absent_by_grade"){
            $sections = $req->selectedSections;
            $students = $req->selectedStudents;
            $shifts = $req->selectedShifts;
        }
        switch($reportType){
            case 'rep_total_absent_by_grade': /// currently working
                $grades_meta = implode(', ',$grades);
                $title = 'Total Absent [Grade] Report'; 
                $meta = [
                    'Grade' =>$grades_meta,
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                if(count($grades) >1)
                    $queryBuilder = Student::whereBetween('grade_id',$grades)
                                    ->with(['rosters' => function($query) use ($fromDate,$toDate){
                                        $query->whereBetween('date',[$fromDate,$toDate])->get();
                                    }])
                                    ->orderBy('grade_id');
                else{
                    $queryBuilder = Student::where('grade_id',$grades)
                                    ->with(['rosters' => function($query) use ($fromDate,$toDate){
                                        $query->whereBetween('date',[$fromDate,$toDate])->get();
                                    }])
                                    ->with(['grade'=> function($query){
                                        $query->pluck('name');
                                    }]);
                }
               
                $columns = [
                    'Student ID' => 'student_id',
                    'Name' =>'name',
                    'Grade' =>function($result){
                        return $result->grade->name;
                    },
                    'Total'=>function($result){
                        return count($result->rosters);
                    },
                    'Absent Days'=>function($result){
                        $absentRosters = 0;
                        foreach($result->rosters as $re){
                            if($re->punch_in == null)
                                $absentRosters++;
                        }
                        return $absentRosters;
                    },
                    'Leave Requested'=>function($result){
                        
                        return '-';
                    },
                    'Leave Approved' =>function($result){
                        
                        return '-';
                    }
                ];
                
                break;
            
                case 'rep_absent': /// on test
                // Report title
                $title = 'Absent Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student_Roster::whereBetween('date',[$fromDate,$toDate])
                                ->whereIn('student_id',$students)
                                ->where('institution_id',$comp_id)
                                ->where(function($query){
                                        $query->where('punch_in',null)->orWhere('punch_out',null);
                                    })
                                ->with('student','shift');//->orderBy('shift_id','ASC');
               //dd($queryBuilder->toSql());
               
               $columns = [
                    'Student ID' => 'student_id',
                    'Name' =>function($result){
                        
                        return $result->student['name'];
                    },
                    'Date'=>'date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
                    'Shift Name'=>function($result){
                        return $result->shift['name'];
                    },
                    'Punch In'=>function($result){
                        $time = explode(' ',$result->punch_in);
                        return $result->punch_in===null?'-':$time[1];
                    },
                    'Punch Out' =>function($result){
                        $time = explode(' ',$result->punch_out);
                        return $result->punch_out===null?'-':$time[1];
                    }
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream($title.".pdf"); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('Absent_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
                
            case 'rep_total_present_by_grade': /// currently working
                $grades_meta = implode(', ',$grades);
                $title = 'Total Present [Grade] Report'; 
                $meta = [
                    'Grade' =>$grades_meta,
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                if(count($grades) >1)
                    $queryBuilder = Student::whereBetween('grade_id',$grades)
                                    ->with(['rosters' => function($query) use ($fromDate,$toDate){
                                        $query->whereBetween('date',[$fromDate,$toDate])->get();
                                    }])
                                    ->orderBy('grade_id');
                else{
                    $queryBuilder = Student::where('grade_id',$grades)
                                    ->with(['rosters' => function($query) use ($fromDate,$toDate){
                                        $query->whereBetween('date',[$fromDate,$toDate])->get();
                                    }])
                                    ->with(['grade'=> function($query){
                                        $query->pluck('name');
                                    }]);
                }
               
               $columns = [
                    'Student ID' => 'student_id',
                    'Name' =>'name',
                    'Grade' =>function($result){
                        return $result->grade->name;
                    },
                    'Total'=>function($result){
                        return count($result->rosters);
                    },
                    'Present Days'=>function($result){
                        $presentRosters = 0;
                        foreach($result->rosters as $re){
                            if($re->punch_in != null)
                                $presentRosters++;
                        }
                        return $presentRosters;
                    },
                    
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream($title.".pdf"); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('Absent_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            
                case 'rep_absent': /// on test
                // Report title
                $title = 'Absent Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student_Roster::whereBetween('date',[$fromDate,$toDate])
                                ->whereIn('student_id',$students)
                                ->where('institution_id',$comp_id)
                                ->where(function($query){
                                        $query->where('punch_in',null)->orWhere('punch_out',null);
                                    })
                                ->with('student','shift');//->orderBy('shift_id','ASC');
               //dd($queryBuilder->toSql());
               
               $columns = [
                    'Student ID' => 'student_id',
                    'Name' =>function($result){
                        
                        return $result->student['name'];
                    },
                    'Date'=>'date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
                    'Shift Name'=>function($result){
                        return $result->shift['name'];
                    },
                    'Punch In'=>function($result){
                        $time = explode(' ',$result->punch_in);
                        return $result->punch_in===null?'-':$time[1];
                    },
                    'Punch Out' =>function($result){
                        $time = explode(' ',$result->punch_out);
                        return $result->punch_out===null?'-':$time[1];
                    }
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream($title.".pdf"); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('Absent_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
                
                case 'rep_annual_summary': // remaining
                // Report title
                $title = 'Annual Performance Summary'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Employee::where(['company_id',$comp_id])->whereBetween([['branch_id',$branches],['dept_id',$departments]])->with('punches');
                //dd($queryBuilder);
                //dd($queryBuilder->toSql());
                $columns = [
                    'Emp Code' => 'emp_id',
                    'Name' =>function($result){
                        return 'name';
                    },
                    'Late IN'=>function($result){
                        if($result->late_in >0 && $result->late_in !=null)
                            return 'Y'; 
                        else    
                            return 'N';
                    },
                    'Late Hrs'=>'late_in',
                    'Early IN'=>function($result){
                        if($result->early_in >0 && $result->early_in !=null)
                            return 'Y'; 
                        else    
                            return 'N';
                    },
                    'Early Hrs' =>function($result){
                        return round($result->early_in/60,2);
                    },
                    'Present' =>'',
                    'Absent' =>'',
                    'W/Off' =>'',
                    'P/HOL' =>'',
                    'Comp' =>'',
                    'PL' =>'',
                    'OD' =>'',
                    'CL' =>'',
                    'SL' =>'',
                    'LWP' =>'',
                    'ESI' =>'',
                    'ACCD' =>'',
                    'Total' =>'',
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        ->setOrientation('landscape')
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        ->setOrientation('landscape')
                        ->download('Annual_attendance_report'); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_attendance': //on test
                // Report title
                $title = 'Attendance Register'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student::where('institution_id',Session::get('company_id'))
                                            ->with(['rosters'=> function ($query) use($fromDate,$toDate) {
                                                $query->whereBetween('date', [$fromDate, $toDate]);
                                            }])
                                            ->with('shift','punch_records')->whereIn('student_id',$students);
                //dd($queryBuilder);
                $columns = [
                    'Student Code' => 'student_id',
                    'Name' =>function($result){
                            return $result->name;
                    },
                    'Present Days'=>function($result){
                        $present_days_count = 0.0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['punch_in'] != null) 
                                $present_days_count+= 0.5;
                            if($result->rosters[$i]['punch_out'] != null)
                                $present_days_count+= 0.5;
                        }
                        return $present_days_count;
                    },
                    'Weekly Off'=>function($result){
                        $w_off_count = 0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['is_holiday'] =="O")
                                $w_off_count++;
                        }
                        return $w_off_count;
                    },
                    // 'P/Holiday'=>function($result){
                    //     $paid_holiday_count =0;
                    //     for($i =0; $i < count($result->rosters); $i++){
                    //         if($result->rosters[$i]['is_holiday'] =="P")
                    //             $paid_holiday_count++;
                    //     }
                    //     return $paid_holiday_count;
                    // },
                    'Leaves' =>function($result){
                        $leave_count =0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['is_holiday'] =="L")
                                $leave_count++;
                        }
                        return $leave_count;
                    },
                    'Absent' => function($result){
                        $absent_days_count = 0.0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['is_holiday'] !="O"){
                                if($result->rosters[$i]['punch_in'] == null)
                                    $absent_days_count+=0.5;
                                if($result->rosters[$i]['punch_out'] == null)
                                    $absent_days_count+=0.5;
                            }
                        }
                        return $absent_days_count;
                    },
                    // 'Total' =>function($result){
                    //     return $result->shift['name'];
                    // },
                    // 'Overtime' =>function($result){
                    //     //dd($result);
                    //     $total_overtime = 0;
                    //     for($i =0; $i < count($result->punch_records); $i++){
                    //         $total_overtime += $result->punch_records[$i]['overtime'];
                    //     }
                    //     return $total_overtime.' min';
                    // },
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->download('Attendance_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                } 
                break;
            
            case 'rep_daily_punch': // incomplete
                // Report title
                $title = 'Punch Details'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Roster::whereIn('employee_id',$employees)
                                        ->whereBetween('date',[$fromDate,$toDate])
                                        ->with('employee','shift', 'punch_record');
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Name' =>function($result){
                            return $result->employee['name'];
                    },
                    'Date'=>'date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
                    'Present Status' =>function($result){
                        $presentStatus = "";
                        if($result->final_half_1 == "PR")
                            $presentStatus = "1-PR,";
                        else
                            $presentStatus = "1-AB,";
                        if($result->final_half_2 == "PR")
                            $presentStatus .= "2-PR";
                        else   
                            $presentStatus .= "2-AB";
                        return $presentStatus;
                    },
                    'Shift Name'=>function($result){
                        return $result->shift['name'];
                    },
                    'IN 1'=>function($result){
                         return $result->punch_record['punch_1'];  
                    },
                    'OUT 1' =>function($result){
                        return $result->punch_record['punch_2'];  
                    },
                    'IN 2'=>function($result){
                        return $result->punch_record['punch_3'];  
                   },
                   'OUT 2' =>function($result){
                       return $result->punch_record['punch_4'];  
                   },
                   'IN 3'=>function($result){
                        return $result->punch_record['punch_5'];  
                    },
                    'OUT 3' =>function($result){
                        return $result->punch_record['punch_6'];  
                    },
                    
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        ->setOrientation('landscape')
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('Daily_punch_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_early_in':  // in progress.. on test.. not showing anything as early_in is 0 in all
                // Report title
                $title = 'Early Comers Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student_Punch::where([['institution_id',Session::get('company_id')]])
                                ->with('shift','student')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('early_in','>',0);
                // $sorted = $queryBuilder->where($queryBuilder['company_id']==1);
                // dd($sorted);
                //dd($queryBuilder);
                $columns = [
                    'Student Code' => function($result){
                        return $result->student['student_id'];
                    },
                    'Name' => function($result){
                        return $result->student['name'];
                    },
                    'In' => 'punch_1',
                    'Out'=>function($result){
                        if($result->punch_6 != null)
                            return $result->punch_6;
                        else if($result->punch_4 != null)
                            return $result->punch_4;
                        else if($result->punch_2 != null)
                            return $result->punch_2;
                        else   
                            return "NA";
                    },
                    'Shift In'=>function($result){
                        return $result->shift['start_time'];
                    },
                    'Shift Out'=>function($result){
                        return $result->shift['end_time'];
                    },
                    'Shift Name' =>function($result){
                        return $result->shift['name'];
                    },
                    'Early By(min)'=>'early_in'
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('early_comers_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_early_out':  //completed
                // Report title
                $title = 'Early Out Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student_Punch::where([['institution_id',Session::get('company_id')]])
                                ->with('roster','student')
                                // ->with(['student',function($query){
                                //     $query->with('shift')->get();
                                // }])
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('early_out','>',0);
                // $sorted = $queryBuilder->where($queryBuilder['company_id']==1);
                // dd($sorted);
                //dd($queryBuilder);
                $columns = [
                    'Student ID' => function($result){
                        return $result->student['student_id'];
                    },
                    'Name' => function($result){
                        return $result->student['name'];
                    },
                    'In' => 'punch_1',
                    'Out'=>function($result){
                        if($result->punch_6 != null)
                            return $result->punch_6;
                        else if($result->punch_4 != null)
                            return $result->punch_4;
                        else if($result->punch_2 != null)
                            return $result->punch_2;
                        else   
                            return "NA";
                    },
                    // 'Shift In'=>function($result){
                    //     return $result->student->shift['start_time'];
                    // },
                    'Shift Out'=>function($result){
                        return $result->shift['end_time'];
                    },
                    'Shift Name' =>function($result){
                        return $result->shift['name'];
                    },
                    'Early By(min)'=>'early_out'
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('early_out_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_student_list':  //completed but tweaking needed 
                // Report title
                $gradesToString = implode(', ',$grades);
                $sectionsToString = implode(', ',$sections);
                $title = 'List Of Students'; 
                $meta = [
                    'Grade' => $gradesToString,
                    'Section ' => $sectionsToString,
                ];
                $queryBuilder = Student::where([['institution_id',Session::get('company_id')]])
                                ->with('grade','shift','section');
                //dd($queryBuilder);
                $columns = [
                    'Student Code' => 'student_id',
                    'Card #' => 'card_number',
                    'Name' =>'name',
                    'Grade'=>function($result){
                        return $result->grade['name'];
                    },
                    'section' =>function($result){
                        return $result->section['name'];
                    },
                    'Father Name'=>'father_name',
                    'Guardian Name'=>'guardian_name',
                    'Shift'=>function($result){
                        return $result->shift['name'];
                    },
                    'Contact 1' =>'contact_1_number',
                    'Contact 2' =>'contact_2_number'
                    
                    // 'Gender'=>function($result){
                    //     if($result->gender ==1)
                    //         return 'Male';
                    //     else
                    //         return 'Female';
                    // }
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4', 'landscape')
                        
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4', 'landscape')
                    ->groupBy('Date')
                    ->download('student_list_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_form_12':  //incomplete
                // Report title
                $title = 'Attendance Register'; 
                $meta = [
                    'Form No.' =>'25',
                    'Prescribed under'=>'Rule 110',
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Employee::where([['company_id',Session::get('company_id')]])->with('rosters');
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Name' =>'name',
                    '01'=>function($result){
                        return $result->rosters[1]['final_half_1']." ".$result->rosters[0]['final_half_2'];
                    }
                    
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        ->setOrientation('landscape')
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->setOrientation('landscape')
                    ->groupBy('Date')
                    ->download('employee_list_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_form_14_el': break; //left
            case 'rep_form_b_cl': break; //left
            case 'rep_late_in':  // completed
                // Report title
                $title = 'Late Comers Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Student_Punch::where([['institution_id',Session::get('company_id')]])
                                ->with('shift','student')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('late_in','>',0);
                $columns = [
                    'Student Code' => function($result){
                        return $result->student['student_id'];
                    },
                    'Name' => function($result){
                        return $result->student['name'];
                    },
                    'In' => 'punch_1',
                    'Out'=>function($result){
                        if($result->punch_6 != null)
                            return $result->punch_6;
                        else if($result->punch_4 != null)
                            return $result->punch_4;
                        else if($result->punch_2 != null)
                            return $result->punch_2;
                        else   
                            return "NA";
                    },
                    'Shift In'=>function($result){
                        return $result->shift['start_time'];
                    },
                    'Shift Out'=>function($result){
                        return $result->shift['end_time'];
                    },
                    'Shift Name' =>function($result){
                        return $result->shift['name'];
                    },
                    'Late By(min)'=>'late_in'
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('late_comers'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_leave_registered': break;
            case 'rep_manpower': // completed
                // Report title
                $title = 'Manpower Report'; 
                $meta = [
                    'Date' => $fromDate,
                ];
                $queryBuilder = Department::where('company_id',Session::get('company_id'))
                                //->whereIn('branch_id',$branches)
                                ->with(['employees'=>function($query){
                                    $query->with('appliedLeaves')->with(['punch_records'=>function($q){
                                        $q->where('punch_date',$this->fromDate);
                                    }]);
                                }])
                                // ->with(['punch_records'=>function($query) use($fromDate){
                                //     $query->where('punch_date',$fromDate);
                                // } ])
                                ->with(['rosters'=> function ($query) use($fromDate) {
                                    $query->where('date', $fromDate);
                                }]);
                //dd($queryBuilder);
                $columns = [
                    'Department' => 'name',
                    'Total' =>function($result){
                        return count($result->employees);
                    },
                    'Absent'=>function ($result){
                        $absent_count = 0;
                        for($i=0;$i<count($result->employees);$i++){
                            if($result->employees[$i]->punch_records==null){
                                $absent_count++;
                            }
                        }
                        //$absent_count = count(Employee::where([['company_id',Session::get('company_id')],['dept_id',$result->department_id]])->get());
                        return $absent_count;
                    },
                    'Leave'=>function($result){
                        $leave_count = 0;
                        $fromDate = new Carbon($this->fromDate);
                        for($i=0;$i<count($result->employees);$i++){
                            for($j=0;$j<count($result->employees[$i]->appliedLeaves);$j++){
                                $leave_from = new Carbon($result->employees[$i]->appliedLeaves[$j]->leave_from);
                                $leave_to = new Carbon($result->employees[$i]->appliedLeaves[$j]->leave_to);
                                $diff_between_leaves_in_days = $leave_from->diffInDays($leave_to);
                                $diff_between_leave_and_fromDate_in_days = $leave_from->diffInDays($fromDate);
                                if($fromDate >= $leave_from){
                                    if($diff_between_leave_and_fromDate_in_days <$result->employees[$i]->appliedLeaves[$j]->posted_days)
                                        $leave_count++;
                                }
                            }
                        }
                        return $leave_count; //// To be changed
                    },
                    'Present'=>function ($result){
                        //dd($result);
                        $present_count = 0;
                        for($i=0;$i<count($result->employees);$i++){
                            //dd($result->employees[$i]->punch_records[0]['punch_1']);
                            if($result->employees[$i]->punch_records!=null )
                                $present_count++;
                        }
                        return $present_count;
                    },
                    
                ];
                if($get_report_type =="pdf"){
                    return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->setCss([
                            '.head-content' => 'border-width: 1px',
                        ])->setPaper('a4')
                        
                        ->stream(); // or download('filename here..') to download pdf;
                }
                else{
                    return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                    ->setCss([
                        '.head-content' => 'border-width: 1px',
                    ])->setPaper('a4')
                    ->groupBy('Date')
                    ->download('Absent_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                }
                break;
            case 'rep_mismatch': $this->mismatchReport($req);break;
            case 'rep_movement': break;
            case 'rep_muster': break;
            case 'rep_overstay': break;
            case 'rep_punch_card': break;
            case 're_register': break;
            case 'rep_canteen_1': break; // left
            case 'rep_canteen_2': break; // left
        }

        if($get_report_type =="pdf"){
            return PdfReport::of($title, $meta, $queryBuilder, $columns)
                ->setCss([
                    '.head-content' => 'border-width: 1px',
                ])->setPaper('a4')
                
                ->stream("Report.pdf"); // or download('filename here..') to download pdf;
        }
        else{
            return ExcelReport::of($title, $meta, $queryBuilder, $columns)
            ->setCss([
                '.head-content' => 'border-width: 1px',
            ])->setPaper('a4')
            ->groupBy('Date')
            ->download('Absent_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
        }
    
        // For displaying filters description on header
        // $meta = [
        //     'Shift Wise Absent Report',
        //     'from' => $fromDate . ' To ' . $toDate,
        // ];
    }
}
