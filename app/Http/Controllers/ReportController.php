<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Branch;
use App\Department;
use App\Employee;
use App\Category;
use App\Shift;
use App\Punch;
use App\Roster;
use App\AppliedLeave;

use App\Student;
use App\Student_Grade;
use App\Student_Section;
use App\Student_Roster;
use App\Student_Punch;

use PDF;
use PdfReport;
use ExcelReport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public $fromDate = null;
    public function viewEmployeeReportPage(){
        $comp_id = Session::get('company_id');
        $branches = Branch::where('company_id', $comp_id)->get();
        $departments = Department::where('company_id', $comp_id)->get();
        $categories = Category:: where('company_id',$comp_id)->get();
        $shifts = Shift::where('company_id', $comp_id)->get();
        
        return view('pages/admin/reports/reportSelection',['branches'=>$branches, 'departments'=>$departments, 'categories'=>$categories, 'shifts'=>$shifts]);
    }
    public function viewStudentReportPage(){
        $institution_id = Session::get('company_id');
        $grades = Student_Grade::where('institution_id', $institution_id)->get();
        $sections = Student_Section::where('institution_id', $institution_id)->get();
        
        return view('pages/admin/reports/reportSelectionStudent',['grades'=>$grades, 'sections'=>$sections ]);
    }
    public function getEmployees(Request $req){
        
        $branches = explode(",",$req->branches);
        $departments = explode(",", $req->departments);
        $categories = explode(",", $req->categories);
        
        $employees = collect([]);
        for($i=0; $i<count($branches); $i++){
            for($j=0; $j<count($departments); $j++){
                for($k=0; $k<count($categories); $k++){
                    $employee = Employee::where([['company_id',Session::get('company_id')],['branch_id',$branches[$i]],['dept_id',$departments[$j]],['category_id',$categories[$k]]])->get();
                    if(count($employee)>0){
                        $employees->push($employee);
                    }
                }
            }
        }
        return response()->json($employees);
    }
    public function getStudents(Request $req){
        $grades = explode(",",$req->grades);
        $sections = explode(",", $req->sections);
        
        $students = collect([]);
        for($i=0; $i<count($grades); $i++){
            for($j=0; $j<count($sections); $j++){
                $student = Student::where([['institution_id',Session::get('company_id')],['grade_id',$grades[$i]],['section_id',$sections[$j]]])->get();
                if(count($student)>0){
                    $students->push($student);
                }
            }
        }
        return response()->json($students);
    }

    public function generateEmployeeReport(Request $req){
        //dd($req);
        $comp_id = Session::get('company_id');
        $fromDate = $req->fromDate;
        $this->fromDate = $fromDate;
        if($req->toDate == null){
            $toDate = new Carbon();
        }else{
            $toDate = $req ->toDate;
        }
        $branches = $req->branchSelected;
        $departments = $req->selectedDepartments;
        $employees = $req->selectedEmployees;
        $reportType = $req->selectedReportType;
        $shifts = $req->selectedShifts;
        $get_report_type = $req->generate_type;

        //dd(count($departments));
        switch($reportType){
            case 'rep_absent': /// Completed
                // Report title
                $title = 'Shift Wise Absent Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Roster::whereBetween('date',[$fromDate,$toDate])
                                ->whereIn('employee_id',$employees)
                                ->where('company_id',Session::get('company_id'))
                                ->where(function($query){
                                        $query->where('final_half_1','AB')->orWhere('final_half_2','AB');
                                    })
                                ->with('employee','shift');//->orderBy('shift_id','ASC');
               //dd($queryBuilder->toSql());
               $columns = [
                    'Emp Code' => 'employee_id',
                    'Name' =>function($result){
                        return $result->employee['name'];
                    },
                    'Date'=>'date', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
                    'Shift Name'=>function($result){
                        return $result->shift['name'];
                    },
                    'Half 1'=>'final_half_1',
                    'Half 2' =>'final_half_2'
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
            case 'rep_annual_summary': // remaining
                // Report title
                $title = 'Annual Performance Summary'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                if(count($departments)>1){
                    $queryBuilder = Employee::where('company_id',$comp_id)->whereBetween('dept_id',array($departments))
                                                ->with('first_shift','second_shift','third_shift','fourth_shift')
                                                ->with(['punch_records'=> function($query) use($fromDate,$toDate){
                                                    $query->whereBetween('punch_date',[$fromDate, $toDate])->with('roster');
                                                }])
                                                ->with(['rosters'=> function ($query) use($fromDate,$toDate) {
                                                     $query->whereBetween('date', [$fromDate, $toDate]);
                                                 }]);

                }else{
                    $queryBuilder = Employee::where('company_id',$comp_id)->where('dept_id',$departments)
                                                ->with('first_shift','second_shift','third_shift','fourth_shift')
                                                ->with(['punch_records'=> function($query) use($fromDate,$toDate){
                                                    $query->whereBetween('punch_date',[$fromDate, $toDate])->with('roster');
                                                }])
                                                ->with(['rosters'=> function ($query) use($fromDate,$toDate) {
                                                    $query->whereBetween('date', [$fromDate, $toDate]);
                                                }]);

                                                
                }
                //dd($queryBuilder);
                //dd($queryBuilder->toSql());
                
                
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Name' =>'name',
                    'Late IN'=>function($result){
                        //dd($result);
                        $lateCount = 0;
                        $lateMinutes = 0;
                        //dd($result->first_shift);
                        $shift_1_timing = $result->first_shift['start_time'];
                        //dd($shift_1_timing);
                        $shift_2_timing = $result->second_shift['start_time'];
                        $shift_3_timing = $result->third_shift['start_time'];
                        $shift_4_timing = $result->fourth_shift['start_time'];

                        $shift_1_id = $result->first_shift['shift_id'];
                        $shift_2_id = $result->second_shift['shift_id'];
                        $shift_3_id = $result->third_shift['shift_id'];
                        $shift_4_id = $result->fourth_shift['shift_id'];
                        
                        foreach($result->punch_records as $punch){
                            // identify the shift in roster and calculate
                            $roster_shift = $punch->roster['shift_id'];
                            $punch_time = new carbon($punch->punch_1);
                            $start_date_time = new Carbon($punch->punch_date);
                            if($roster_shift == $shift_1_id){
                                $start_date_time->addHours(substr($shift_1_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_1_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->first_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_2_id){
                                $start_date_time->addHours(substr($shift_2_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_2_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->second_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_3_id){
                                $start_date_time->addHours(substr($shift_3_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_3_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->third_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_4_id){
                                $start_date_time->addHours(substr($shift_4_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_4_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->fourth_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }
                           
                        }
                        return $lateCount;
                        // if($result->late_in >0 && $result->late_in !=null)
                        //     return 'Y'; 
                        // else    
                        //     return 'N';
                    },
                    'Late Hrs'=>function($result){
                        //dd($result);
                        $lateCount = 0;
                        $lateMinutes = 0;
                        //dd($result->first_shift['start_time']);
                        $shift_1_timing = $result->first_shift['start_time'];
                        $shift_2_timing = $result->second_shift['start_time'];
                        $shift_3_timing = $result->third_shift['start_time'];
                        $shift_4_timing = $result->fourth_shift['start_time'];

                        $shift_1_id = $result->first_shift['shift_id'];
                        $shift_2_id = $result->second_shift['shift_id'];
                        $shift_3_id = $result->third_shift['shift_id'];
                        $shift_4_id = $result->fourth_shift['shift_id'];

                        foreach($result->punch_records as $punch){
                            // 
                            $roster_shift = $punch->roster['shift_id'];
                            $punch_time = new carbon($punch->punch_1);
                            $start_date_time = new Carbon($punch->punch_date);

                            if($roster_shift == $shift_1_id){
                                $start_date_time->addHours(substr($shift_1_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_1_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->first_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_2_id){
                                $start_date_time->addHours(substr($shift_2_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_2_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->second_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_3_id){
                                $start_date_time->addHours(substr($shift_3_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_3_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->third_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_4_id){
                                $start_date_time->addHours(substr($shift_4_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_4_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) >$result->fourth_shift['grace_late'] && $punch_time > $start_date_time){
                                    $lateCount++;
                                    $lateMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }
                        }
                        $late_hrs = floor($lateMinutes/60);
                        if($late_hrs<1)
                            $late_hrs = 0;
                        $late_minutes= $lateMinutes%60;

                        return $late_hrs.":".$late_minutes;
                    },
                    'Early IN'=>function($result){
                        $earlyCount = 0;
                        $earlyMinutes = 0;

                        $shift_1_timing = $result->first_shift['start_time'];
                        $shift_2_timing = $result->second_shift['start_time'];
                        $shift_3_timing = $result->third_shift['start_time'];
                        $shift_4_timing = $result->fourth_shift['start_time'];

                        $shift_1_id = $result->first_shift['shift_id'];
                        $shift_2_id = $result->second_shift['shift_id'];
                        $shift_3_id = $result->third_shift['shift_id'];
                        $shift_4_id = $result->fourth_shift['shift_id'];

                        foreach($result->punch_records as $punch){
                            
                            $start_date_time = new Carbon($punch->punch_date);
                            $start_date_time->addHours(substr($shift_1_timing,0,2));
                            $start_date_time->addMinutes(substr($shift_1_timing,3,2));

                            $punch_time = new carbon($punch->punch_1);

                            if($start_date_time->diffInMinutes($punch_time) > $result->first_shift['grace_early'] && $punch_time < $start_date_time){ // early will be calculated if greater than grace period
                                $earlyCount++;
                                $earlyMinutes += $punch_time->diffInMinutes($start_date_time);
                            }
                            
                        }
                        //dd($earlyMinutes);
                        return $earlyCount;
                        // if($result->early_in >0 && $result->early_in !=null)
                        //     return 'Y'; 
                        // else    
                        //     return 'N';
                    },
                    'Early Hrs' =>function($result){
                        $earlyCount = 0;
                        $earlyMinutes = 0;
                        //dd($result->first_shift['start_time']);
                        $shift_1_timing = $result->first_shift['start_time'];
                        $shift_2_timing = $result->second_shift['start_time'];
                        $shift_3_timing = $result->third_shift['start_time'];
                        $shift_4_timing = $result->fourth_shift['start_time'];

                        $shift_1_id = $result->first_shift['shift_id'];
                        $shift_2_id = $result->second_shift['shift_id'];
                        $shift_3_id = $result->third_shift['shift_id'];
                        $shift_4_id = $result->fourth_shift['shift_id'];

                        foreach($result->punch_records as $punch){
                            // 
                            $roster_shift = $punch->roster['shift_id'];
                            $punch_time = new carbon($punch->punch_1);
                            $start_date_time = new Carbon($punch->punch_date);

                            if($roster_shift == $shift_1_id){
                                $start_date_time->addHours(substr($shift_1_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_1_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) <= $result->first_shift['grace_early'] && $punch_time < $start_date_time){
                                    $earlyCount++;
                                    $earlyMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_2_id){
                                $start_date_time->addHours(substr($shift_2_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_2_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) <= $result->second_shift['grace_early'] && $punch_time < $start_date_time){
                                    $earlyCount++;
                                    $earlyMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_3_id){
                                $start_date_time->addHours(substr($shift_3_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_3_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) <= $result->third_shift['grace_early'] && $punch_time < $start_date_time){
                                    $earlyCount++;
                                    $earlyMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }else if($roster_shift == $shift_4_id){
                                $start_date_time->addHours(substr($shift_4_timing,0,2));
                                $start_date_time->addMinutes(substr($shift_4_timing,3,2));
                                
                                if($punch_time->diffInMinutes($start_date_time) <= $result->fourth_shift['grace_early'] && $punch_time < $start_date_time){
                                    $earlyCount++;
                                    $earlyMinutes += $punch_time->diffInMinutes($start_date_time);
                                }
                            }
                        }
                        $early_hrs = floor($earlyMinutes/60);
                        if($early_hrs<1)
                            $early_hrs = 0;
                        $early_minutes= $earlyMinutes%60;

                        return $early_hrs.":".$early_minutes;
                        
                    },
                    'Present' =>function($result){
                        $present_days = 0;
                        //dd($result);
                        foreach($result->punch_records as $punch){
                            if($punch->roster['final_half_1']=="PR")
                                $present_days += 0.5;
                            if($punch->roster['final_half_2']=="PR")
                                $present_days += 0.5;
                        }

                        return $present_days;
                    },
                    'Absent' =>function($result){
                        $absent_days = 0;
                        //dd($result);
                        foreach($result->rosters as $roster){
                            if($roster['final_half_1']=="AB")
                                $absent_days += 0.5;
                            if($roster['final_half_2']=="AB")
                                $absent_days += 0.5;
                        }

                        return $absent_days;
                    },
                    'W/Off' =>function($result){
                        $weekly_off_days = 0;
                        foreach($result->rosters as $roster){
                            if($roster['is_holiday']=="O")
                                $weekly_off_days++;
                        }
                        return $weekly_off_days;
                    },
                    'P/HOL' =>function($result){
                        $holidays = 0;
                        foreach($result->rosters as $roster){
                            if($roster['is_holiday']=="H")
                                $holidays++;
                        }
                        return $holidays;
                    },
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
            case 'rep_attendance': //completed
                // Report title
                $title = 'Attendance Register'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Employee::where('company_id',Session::get('company_id'))
                                            ->with(['rosters'=> function ($query) use($fromDate,$toDate) {
                                                $query->whereBetween('date', [$fromDate, $toDate]);
                                            }])
                                            ->with('first_shift','punch_records')->whereIn('employee_id',$employees);
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Name' =>function($result){
                            return $result->name;
                    },
                    'Pres'=>function($result){
                        $present_days_count = 0.0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['final_half_1'] =="PR") 
                                $present_days_count+= 0.5;
                            if($result->rosters[$i]['final_half_2'] =="PR")
                                $present_days_count+= 0.5;
                        }
                        return $present_days_count;
                    },
                    'W/Off'=>function($result){
                        $w_off_count = 0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['is_holiday'] =="O")
                                $w_off_count++;
                        }
                        return $w_off_count;
                    },
                    'P/Holiday'=>function($result){
                        $paid_holiday_count =0;
                        for($i =0; $i < count($result->rosters); $i++){
                            if($result->rosters[$i]['is_holiday'] =="P")
                                $paid_holiday_count++;
                        }
                        return $paid_holiday_count;
                    },
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
                                if($result->rosters[$i]['final_half_1'] =="AB")
                                    $absent_days_count+=0.5;
                                if($result->rosters[$i]['final_half_2'] =="AB")
                                    $absent_days_count+=0.5;
                            }
                        }
                        return $absent_days_count;
                    },
                    'Total' =>function($result){
                        return $result->shifts[0]['name'];
                    },
                    'Overtime' =>function($result){
                        //dd($result);
                        $total_overtime = 0;
                        for($i =0; $i < count($result->punch_records); $i++){
                            $total_overtime += $result->punch_records[$i]['overtime'];
                        }
                        return $total_overtime.' min';
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
                    ->download('Attendance_report_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
                } 
                break;
            
            case 'rep_daily_punch': // completed
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
            case 'rep_early_in':  // completed
                // Report title
                $title = 'Early Comers Report'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Punch::where([['company_id',Session::get('company_id')]])
                                ->with('shift','employee')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('early_in','>',0);
                // $sorted = $queryBuilder->where($queryBuilder['company_id']==1);
                // dd($sorted);
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => function($result){
                        return $result->employee['employee_id'];
                    },
                    'Name' => function($result){
                        return $result->employee['name'];
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
                $queryBuilder = Punch::where([['company_id',Session::get('company_id')]])
                                ->with('shift','employee')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('early_out','>',0);
                // $sorted = $queryBuilder->where($queryBuilder['company_id']==1);
                // dd($sorted);
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => function($result){
                        return $result->employee['employee_id'];
                    },
                    'Name' => function($result){
                        return $result->employee['name'];
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
            case 'rep_employee_list':  //completed but tweaking needed 
                // Report title
                $title = 'List Of Employees'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Employee::where([['company_id',Session::get('company_id')]])
                                ->with('department','first_shift','designation');
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Card #' => 'card_number',
                    'Name' =>'name',
                    'Department'=>function($result){
                        return $result->department['name'];
                    },
                    'Father Name'=>'father_name',
                    'Shift'=>function($result){
                        return $result->first_shift[0]['name'];
                    },
                    'Designation' =>function($result){
                        return $result->designation['name'];
                    },
                    'Category'=>function($result){
                        return $result->category['name'];
                    },
                    'Sex'=>function($result){
                        if($result->gender ==1)
                            return 'Male';
                        else
                            return 'Female';
                    }
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
                    ->download('employee_list_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
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
                $queryBuilder = Punch::where([['company_id',Session::get('company_id')]])
                                ->with('shift','employee')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('late_in','>',0);
                $columns = [
                    'Emp Code' => function($result){
                        return $result->employee['employee_id'];
                    },
                    'Name' => function($result){
                        return $result->employee['name'];
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
    
        // For displaying filters description on header
        $meta = [
            'Shift Wise Absent Report',
            'from' => $fromDate . ' To ' . $toDate,
        ];

        // Do some querying..

        
        // $queryBuilder = User::select(['name', 'balance', 'registered_at'])
        //                     ->whereBetween('registered_at', [$fromDate, $toDate])
        //                     ->orderBy($sortBy);

        // // Set Column to be displayed
        // $columns = [
        //     'Name' => 'name',
        //     'Registered At', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
        //     'Total Balance' => 'balance',
        //     'Status' => function($result) { // You can do if statement or any action do you want inside this closure
        //         return ($result->balance > 100000) ? 'Rich Man' : 'Normal Guy';
        //     }
        // ];

        // /*
        //     Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).

        //     - of()         : Init the title, meta (filters description to show), query, column (to be shown)
        //     - editColumn() : To Change column class or manipulate its data for displaying to report
        //     - editColumns(): Mass edit column
        //     - showTotal()  : Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
        //     - groupBy()    : Show total of value on specific group. Used with showTotal() enabled.
        //     - limit()      : Limit record to be showed
        //     - make()       : Will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
        // */
        // return PdfReport::of($title, $meta, $queryBuilder, $columns)
        //                 ->editColumn('Registered At', [
        //                     'displayAs' => function($result) {
        //                         return $result->registered_at->format('d M Y');
        //                     }
        //                 ])
        //                 ->editColumn('Total Balance', [
        //                     'displayAs' => function($result) {
        //                         return thousandSeparator($result->balance);
        //                     }
        //                 ])
        //                 ->editColumns(['Total Balance', 'Status'], [
        //                     'class' => 'right bold'
        //                 ])
        //                 ->showTotal([
        //                     'Total Balance' => 'point' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
        //                 ])
        //                 ->limit(20)
        //                 ->stream(); // or download('filename here..') to download pdf
    }
    public function generateReportStudent(Request $req){
        $comp_id = Session::get('company_id');
        $fromDate = $req->fromDate;
        $this->fromDate = $fromDate;
        $toDate = $req ->toDate;
        $grades = $req->selectedGrades;
        $sections = $req->selectedSections;
        $students = $req->selectedStudents;
        $reportType = $req->selectedReportType;
        $shifts = $req->selectedShifts;
        $get_report_type = $req->generate_type;
        //dd($grades);
        switch($reportType){
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
                        return $result->punch_in===null?'-':$result->punch_in;
                    },
                    'Punch Out' =>function($result){
                        return $result->punch_out===null?'-':$result->punch_out;
                    }
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
            
            case 'rep_daily_punch': // completed
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
                $queryBuilder = Punch::where([['company_id',Session::get('company_id')]])
                                ->with('shift','employee')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('early_out','>',0);
                // $sorted = $queryBuilder->where($queryBuilder['company_id']==1);
                // dd($sorted);
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => function($result){
                        return $result->employee['employee_id'];
                    },
                    'Name' => function($result){
                        return $result->employee['name'];
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
            case 'rep_employee_list':  //completed but tweaking needed 
                // Report title
                $title = 'List Of Employees'; 
                $meta = [
                    'From' => $fromDate,
                    'To ' => $toDate,
                ];
                $queryBuilder = Employee::where([['company_id',Session::get('company_id')]])
                                ->with('department','first_shift','designation');
                //dd($queryBuilder);
                $columns = [
                    'Emp Code' => 'employee_id',
                    'Card #' => 'card_number',
                    'Name' =>'name',
                    'Department'=>function($result){
                        return $result->department['name'];
                    },
                    'Father Name'=>'father_name',
                    'Shift'=>function($result){
                        return $result->first_shift[0]['name'];
                    },
                    'Designation' =>function($result){
                        return $result->designation['name'];
                    },
                    'Category'=>function($result){
                        return $result->category['name'];
                    },
                    'Sex'=>function($result){
                        if($result->gender ==1)
                            return 'Male';
                        else
                            return 'Female';
                    }
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
                    ->download('employee_list_'.$fromDate.'-'.$toDate); // or download('filename here..') to download pdf;
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
                $queryBuilder = Punch::where([['company_id',Session::get('company_id')]])
                                ->with('shift','employee')
                                ->whereBetween('punch_date',[$fromDate,$toDate])
                                ->where('late_in','>',0);
                $columns = [
                    'Emp Code' => function($result){
                        return $result->employee['employee_id'];
                    },
                    'Name' => function($result){
                        return $result->employee['name'];
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
    
        // For displaying filters description on header
        $meta = [
            'Shift Wise Absent Report',
            'from' => $fromDate . ' To ' . $toDate,
        ];
    }

    public function pdfview(Request $request){
        // ini_set('max_execution_time', 0);
        $leaves = AppliedLeave::all();
        // dd($request);
        //$pdf = \App::make('dompdf.wrapper');
        view()->share('leaves',$leaves);
        $pdf = PDF::loadView('pages.reports.pdfview',['leaves'=>$leaves]);
        return $pdf->download('pdfview.pdf');

        return view('pages.reports.pdfview');
        //return $pdf;
        //return $pdf->download('test.pdf');
    }
    public function mismatchReport(Request $request){
        $employees = Employee::where('company_id',Session::get('company_id'))->with(['punch_records'=>function($query){
            $query->where([['punch_1','!=',null],['punch_2',null]])->orWhere([['punch_3','!=',null],['punch_4',null]])->orWhere([['punch_5','!=',null],['punch_6',null]]);
        }])->get();
        $pdf = app('dompdf.wrapper'); 
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.reports.mismatch',['employees'=>$employees])->setPaper('A4','landscape');
        return $pdf->stream('mismatch.pdf');
    }
}
