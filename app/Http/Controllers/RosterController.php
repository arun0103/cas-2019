<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Branch;
use App\Employee;
use App\Roster;
use App\Holiday;
use App\Shift;

use App\Student;
use App\Grade;
use App\Section;
use App\Student_Grade;
use App\Student_Section;
use App\Student_Roster;
use App\Student_Shift;

use Session;
use Carbon\Carbon;

class RosterController extends Controller
{
    public function rosters(){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        $rosterDetail = Roster::where('company_id',$company_id)->with('branch','department','shift','employee')->get();
        $shifts = Shift::where('company_id',$company_id)->get();
        //dd($rosterDetail);
        return view('pages/admin/roster/rosters',['allBranches'=>$branches, 'shifts'=>$shifts, 'rosterDetail'=>$rosterDetail]);
    }
    public function generate(Request $request){
        $company_id = Session::get('company_id');
        $branch = $request->input('selectedBranches');
        $year = $request->input('year');
        $month = $request->input('selectedMonth');
        $number_of_days = cal_days_in_month(CAL_GREGORIAN,(int)$month,(int)$year);
        $twoDigitMonth = (int)$month<10?"0".$month:$month;
        //dd($twoDigitMonth);
        $isWeekOff = false;
        if($branch !=null){
            $employeeCount = 0;
            foreach($branch as $b){
                $checkDate = $year."-".($month>9?$month:"0".$month)."-01";
                
                $employees = Employee::where([['company_id',$company_id],['branch_id',$b]])->get();
                $employeeCount +=count($employees);
                foreach($employees as $employee){
                    for($i=1; $i<=$number_of_days; $i++){
                        $twoDigitDay = $i<10?"0".$i:$i;
                        $date = $year."-".$twoDigitMonth."-".$twoDigitDay;

                        $check_roster_exists = Roster::where([['company_id',$company_id],['employee_id',$employee->employee_id],['date',$date]])->get();
                        if(count($check_roster_exists)>0)
                            continue;

                        $dateDay =  date('D', strtotime($date." 00:00"));
                        $dateDay_number = 0;
                        $day_status = "W";
                        switch($dateDay){
                            case "Mon": $dateDay_number = 1;break;
                            case "Tue": $dateDay_number = 2;break;
                            case "Wed": $dateDay_number = 3;break;
                            case "Thu": $dateDay_number = 4;break;
                            case "Fri": $dateDay_number = 5;break;
                            case "Sat": $dateDay_number = 6;break;
                            case "Sun": $dateDay_number = 0;break;
                        }
                        
                        if($employee->week_off_day == $dateDay_number){
                            $isWeekOff = true;
                            $day_status = "O";
                        }else{
                            $isWeekOff = false;
                            $day_status = "W";
                        }
                        //echo("Company Id : ".$company_id."\tShift Id : ".$employee->shift_1."Employee Id :".$employee->employee_id."\tDate : ".$date."<br>");
                        $holiday_check = Holiday::where([['company_id',$company_id],['holiday_date',$date]])->first();
                        
                        if($holiday_check != null){
                            $day_status = "H";
                        }
                        
                        $roster = new Roster([
                            'employee_id'=>$employee->employee_id,
                            'company_id'=>$company_id,
                            'branch_id'=>$employee->branch_id,
                            'department_id'=>$employee->dept_id,
                            'shift_id'=>$employee->shift_1,
                            'date'=>$date,
                            'is_holiday'=>$day_status,
                            'final_half_1'=>"AB",
                            'final_half_2'=>"AB"
                        ]);
                        $roster->save();
                    }
                }
            }
        }   
        if($employeeCount !=0)
            return Redirect::back()->with('successMessage',"Rosters generated for ".$employeeCount." employees");
        else
            return Redirect::back()->with('failMessage', "Rosters already exists!");
    }
    
    public function generateStudentRoster(Request $request){
        $institution_id = Session::get('company_id');
        $grades = $request->input('selectedGrades');
        $year = $request->input('year');
        $month = $request->input('selectedMonth');
        $number_of_days = cal_days_in_month(CAL_GREGORIAN,(int)$month,(int)$year);
        $twoDigitMonth = (int)$month<10?"0".$month:$month;
        //dd($twoDigitMonth);
        $isWeekOff = false;
        if($grades !=null){
            $studentCount = 0;
            
            foreach($grades as $grade){
                $checkDate = $year."-".($month>9?$month:"0".$month)."-01";
                
                $students = Student::where([['institution_id',$institution_id],['grade_id',$grade]])
                                    ->with('grade','section')->get();
                //dd($students);
                $studentCount += count($students);
                foreach($students as $student){
                    for($i=1; $i<=$number_of_days; $i++){
                        $twoDigitDay = $i<10?"0".$i:$i;
                        $date = $year."-".$twoDigitMonth."-".$twoDigitDay;

                        $check_roster_exists = Student_Roster::where([['institution_id',$institution_id],['student_id',$student->student_id],['date',$date]])->get();
                        if(count($check_roster_exists)>0)
                            continue;

                        $dateDay =  date('D', strtotime($date." 00:00"));
                        $dateDay_number = 0;
                        $day_status = "C";
                        switch($dateDay){
                            case "Mon": $dateDay_number = 1;break;
                            case "Tue": $dateDay_number = 2;break;
                            case "Wed": $dateDay_number = 3;break;
                            case "Thu": $dateDay_number = 4;break;
                            case "Fri": $dateDay_number = 5;break;
                            case "Sat": $dateDay_number = 6;break;
                            case "Sun": $dateDay_number = 0;break;
                        }
                        
                        if($student->week_off_day == $dateDay_number){
                            $isWeekOff = true;
                            $day_status = "O";
                        }else{
                            $isWeekOff = false;
                            $day_status = "C";
                        }
                        //echo("Company Id : ".$company_id."\tShift Id : ".$employee->shift_1."Employee Id :".$employee->employee_id."\tDate : ".$date."<br>");
                        $holiday_check = Holiday::where([['company_id',$institution_id],['holiday_date',$date]])->first();
                        
                        if($holiday_check != null){
                            $day_status = "H";
                        }
                        
                        $roster = new Student_Roster([
                            'student_id'=>$student->student_id,
                            'institution_id'=>$institution_id,
                            'shift_id'=>$student->shift_id,
                            'date'=>$date,
                            'is_holiday'=>$day_status,
                            'punch_in'=>null,
                            'punch_out'=>null
                        ]);
                        $roster->save();
                    }
                }
            }
        }   
        if($studentCount !=0)
            return Redirect::back()->with('successMessage',"Rosters generated for ".$studentCount." students");
        else
            return Redirect::back()->with('failMessage', "Rosters already exists!");
    }
    public function view(Request $request){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        $shifts = Shift::where('company_id',$company_id)->get();
        $branch_id = $request->input('selectedBranchView');
        $employee_id = $request->input('selectedEmployeeView');
        
        $date = $request->input('dateView');
        
        $rosterDetail = Roster::where([['company_id',$company_id],['employee_id',$employee_id],['date',$date]])->with('branch','department','shift','employee')->get();
        //dd($rosterDetail);
        if($rosterDetail != null){
            return view('pages/admin/roster/rosters',['allBranches'=>$branches, 'shifts'=>$shifts, 'rosterDetail'=>$rosterDetail]);
        }else{
            return Redirect::back()->with('failMessage','No Roster found !');
        }
    }
    public function viewStudentRoster(Request $request, $selectedMonth){
        $month = $selectedMonth;
        $year = Carbon::now()->year;
        $fromDate = $year.'/'.($month<10?'0'.$month:$month).'/01';
        $toDate = $year.'/'.($month<10?'0'.$month:$month).'/31';
        //dd($fromDate);
        $institution_id = Session::get('company_id');
        $grades = Student_Grade::where('institution_id',$institution_id)->get();
        $sections = Student_Section::where('institution_id',$institution_id)->get();
        $shifts = Student_Shift::where('institution_id',$institution_id)->get();
        // $branch_id = $request->input('selectedBranchView');
        // $employee_id = $request->input('selectedEmployeeView');
        
        //$date = $request->input('dateView');
        
        // $rosterDetail = Student_Roster::where([['institution_id',$institution_id]])
        //                                 ->with('student')
        //                                 ->get();

        $studentRosterDetail = Student::where('institution_id',$institution_id)
                                    ->with('shift','grade','section')
                                    ->with(['rosters'=> function($query) use($fromDate, $toDate){
                                        $query->whereBetween('date',[$fromDate, $toDate]);
                                    }])
                                    ->get();
        //dd($studentRosterDetail[0]);
        if($studentRosterDetail != null){
            return view('pages/admin/roster/student_roster',['allGrades'=>$grades,'sections'=>$sections, 'shifts'=>$shifts, 'rosterDetail'=>$studentRosterDetail]);
        }else{
            return Redirect::back()->with('failMessage','No Roster found !');
        }
    }

    public function getRosterData($id){
        $data = Roster::where('id',$id)->with('branch','shift','employee')->first();
        return response()->json($data);
    }
    public function getStudentRosterData($id){
        $data = Student_Roster::where('id',$id)->with('shift')->with(['student' =>function($query){
                $query->with('grade','section')->get();
                }])->first();
        return response()->json($data);
    }
    public function updateRoster(Request $req){
        $rosterToUpdate = Roster::where('id',$req->id)->first();
        $rosterToUpdate->shift_id = $req->shift;
        $rosterToUpdate->is_holiday = $req->dayStatus;
        $rosterToUpdate->updated_at = Carbon::now();
        $rosterToUpdate->save();
        $updatedRoster = Roster::where('id',$req->id)->with('branch','department','shift','employee')->first();
        
        return response()->json($updatedRoster);
    }
    public function updateStudentRoster(Request $req){
        $rosterToUpdate = Student_Roster::where('id',$req->id)->first();
        $rosterToUpdate->shift_id = $req->shift;
        $rosterToUpdate->is_holiday = $req->dayStatus;
        $rosterToUpdate->updated_at = Carbon::now();
        $rosterToUpdate->save();
        $updatedRoster = Student_Roster::where('id',$req->id)->with('shift')
            ->with(['student' =>function($query){
                $query->with('grade','section')->get();
                }])->first();
        
        return response()->json($updatedRoster);
    }

    public function deleteRoster($id){
        $roster = Roster::where('id',$id)->delete();
        return response()->json($roster);
    }

    public function deleteStudentRoster($id){
        $roster = Student_Roster::where('id',$id)->delete();
        return response()->json($roster);
    }

    public function updateRosterDetails(Request $req){
        $rosterToUpdate = Roster::where([['company_id',$req->company_id],['employee_id',$req->emp_id],['date',$req->punch_date]])->first();
        $rosterToUpdate->final_half_1 = $req->final_half_1;
        $rosterToUpdate->final_half_2 = $req->final_half_2;
        $rosterToUpdate->is_holiday = $req->status;
        $rosterToUpdate->save();
        return response()->json($rosterToUpdate);
    }
    public function viewStudentRosterOfDay(Request $req){

    }
}
