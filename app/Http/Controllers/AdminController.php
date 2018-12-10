<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

use App\Branch;
use App\Company;
use App\Department;
use App\Designation;
use App\Category;
use App\Holiday;
use App\Shift;
use App\LeaveMaster;
use App\LeaveTypes;
use App\CompanyLeave;
use App\RawData;
use App\Punch;
use App\Roster;
use App\Employee;
use App\AppliedLeave;
use App\LeaveQuota;
use Session;
use Illuminate\Support\Facades\Storage;
use File;

ini_set('max_execution_time', 180); //3 minutes

class AdminController extends Controller
{
    ////// Function to upload raw file
    public function uploadFile(Request $request){
        $file = $request->file('fileToUpload');
   
        $fileName = $file->getClientOriginalName();
        $path = $file->getRealPath();
        Storage::disk('local')->put($fileName, file_get_contents($file));

        $contents = File::get(storage_path('app/'.$fileName));
        $rows =explode("\n", $contents);
        $index = 0;
        $totalRows = count($rows)-1;
        $addedRows = 0;
        $rejectedRows = 0;
        foreach($rows as $row){
            if($index>0 && $index<count($rows)){
                $row = substr($row,0,strlen($row)-1); // removing \r from row
                $rowData = explode("\t",$row);
                if(count($rowData)==7){ // verifying that row contains 7 columns
                    $punchTime = str_replace('/','-',$rowData[6]);
                    $check = RawData::where([['card_number',$rowData[2]],['machine_id',$rowData[1]],['punch_time',str_replace('/','-',$rowData[6])]])->get();
                    if(count($check)==0){
                        $data = new RawData([
                            'card_number'=>$rowData[2],
                            'machine_id'=>$rowData[1],
                            'in_out'=>'U',
                            'punch_time'=>$punchTime,
                            'status'=> false
                        ]);
                        $data->save();
                        $addedRows++;
                    }else{
                        $rejectedRows++;
                    }
                }
            }
            $index++;
        }
        $message = [
            'name'=>$file->getClientOriginalName(),
            'extension'=>$file->getClientOriginalExtension(),
            'mimeType'=>$file->getMimeType(),
            'total'=>$totalRows,
            'added'=>$addedRows,
            'rejected'=>$rejectedRows
        ];
        Storage::delete($fileName);
        
        return back()->with('message',$message);
        //$openFile = fopen($path.'\\'.$fileName,'r');

        //echo 'Content: '.$openFile;

        //Display File Name
        
        // echo 'File Name: '.$file->getClientOriginalName();
        // echo '<br>';
    
        // //Display File Extension
        // echo 'File Extension: '.$file->getClientOriginalExtension();
        // echo '<br>';
    
        // //Display File Real Path
        // echo 'File Real Path: '.$file->getRealPath();
        // echo '<br>';
    
        // //Display File Size
        // echo 'File Size: '.$file->getSize();
        // echo '<br>';
    
        // //Display File Mime Type
        // echo 'File Mime Type: '.$file->getMimeType();
        
        //Move Uploaded File
        //$destinationPath = 'uploads';
        //$file->move($destinationPath,$file->getClientOriginalName());
        
        
    }

    // public function addHoliday(){

    //     $holiday = new Holiday([
    //         'holiday_description'=>request('holiday_description'),
    //         'holiday_date'=>request('holiday_date'),

    //         'company_id'=>Session::get('company_id'), // these needed to be changed
    //         'branch_id'=>1  // these needed to be changed
    //     ]);
    //     if($holiday->save()){
    //         return redirect('/admin/holiday/add')->with('status', 'Holiday created!');
    //     }
    // }
    public function getHolidays(){
        $holidays = Holiday::all();
        return view('pages/admin/holiday/viewHolidays',['holidays'=>$holidays]);
    }

    public function showAddEmployeePage(){
        $branches = Branch::all();
        $companies = Company::all();
        $categories = Category::all();
        $designations = Designation::all();
        return view('pages/admin/employee/addEmployee',['branches'=>$branches, 'companies'=>$companies, 'categories'=>$categories, 'designations'=>$designations]);
    }

    public function addEmployee(){
        $employee= new Employee([
            'employee_id'=>request('employee_id'),
            'name'=>request('employee_name'),
            'email'=>request('employee_id'),
            'country'=>request('employee_id'),
            'state'=>request('employee_id'),
            'city'=>request('employee_id'),
            'street_address_1'=>request('employee_id'),
            'street_address_2'=>request('employee_id'),
            'postal_code'=>request('employee_id'),
            'dob'=>request('employee_id'),
            'gender'=>request('employee_id'),
            'marital_status'=>request('employee_id'),
            'anniversary'=>request('employee_id'),
            'father_name'=>request('employee_id'),
            'educational_qualification'=>request('employee_id'),
            'professional_qualification'=>request('employee_id'),
            'experience'=>request('employee_id'),

            'card_number'=>request('employee_id'),
            'dept_id'=>request('employee_id'),
            'category_id'=>request('employee_id'),
            'company_id'=>request('employee_id'),
            'branch_id'=>request('employee_id'),
            'designation_id'=>request('employee_id'),
            'Permanent_Temporary'=>request('employee_id'),

            'week_off_day'=>request('employee_id'),
            'additional_off_day'=>request('employee_id'),
            'additional_off_week'=>request('employee_id'),
            'shift_1'=>request('employee_id'),
            'shift_2'=>request('employee_id'),
            'shift_3'=>request('employee_id'),
            'shift_4'=>request('employee_id'),
            'change_by_week'=>request('employee_id'),
            'change_after_days'=>request('employee_id'),
            'changed_on_day'=>request('employee_id'),
            'half_day_shift'=>request('employee_id'),
            'half_day_on'=>request('employee_id'),
            'comp_off_applicable'=>request('employee_id'),
            'overtime_applicable'=>request('employee_id'),
            'reporting_officer_1'=>request('employee_id'),
            'reporting_officer_2'=>request('employee_id'),
            'joining_date'=>request('employee_id'),
            'leaving_date'=>request('employee_id'),
            'referred_by'=>request('employee_id'),
            
            'ESI_number'=>request('employee_id'),
            'PF_number'=>request('employee_id'),
            'UAN_number'=>request('employee_id'),
            'PAN_number'=>request('employee_id'),
            'wage_type'=>request('employee_id'),

            'bank_name'=>request('employee_id'),
            'IFSC_code'=>request('employee_id'),
            'bank_branch'=>request('employee_id'),
            'bank_account_number'=>request('employee_id'),
        ]);
    }

    public function editPunch(){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        $shifts = Shift::where('company_id',$company_id)->get();
        return view('pages/admin/punch/manualEntry',['branches'=>$branches, 'shifts'=>$shifts]);
    }

    public function insertPunchRecord(Request $req){
        
        $employeeDetails = Employee::where([['company_id',Session::get('company_id')],['employee_id',$req->employee_id]])->first();
        $shiftDetails = Shift::where('shift_id',$req->shift_id)->first();
        
        switch($employeeDetails->auto_shift){
            case null: break;
            case false: 
                $todayRoster = Roster::where('roster_id',$req->roster_id)->first();
                $shiftDetails = Shift::where('shift_id',$todayRoster->shift_id)->first();
                break;
            case 1:
            case true: 
                $shift_1_emp= null;
                $shift_2_emp= null;
                $shift_3_emp = null;
                $shift_4_emp = null;
                $shift_1_time = null;
                $shift_2_time = null;
                $shift_3_time = null;
                $shift_4_time = null;
                $dt_punch_1 = new DateTime($req->punch_1);
                $punch_1 = Carbon::instance($dt_punch_1);   
                $shift_time;
                if($employeeDetails->shift_1!= null){
                    $shift_1_emp = Shift::where('shift_id',$employeeDetails->shift_1)->first();
                    $shiftDetails =  $shift_1_emp;
                    $shift_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time)));
                }
                if($employeeDetails->shift_2!=null){
                    $shift_2_emp = Shift::where('shift_id',$employeeDetails->shift_2)->first();
                    $shift_1_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_1_emp->start_time)));
                    $shift_2_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_2_emp->start_time)));
                    $diff_in_minutes_1 = $shift_1_time->diffInMinutes($punch_1);
                    $diff_in_minutes_2 = $shift_2_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_1<0)
                        $diff_in_minutes_1 *= -1;
                    if($diff_in_minutes_2<0)
                        $diff_in_minutes_2 *= -1;

                    if($diff_in_minutes_1 > $diff_in_minutes_2){
                        $shiftDetails =  $shift_2_emp;
                        $shift_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time)));
                    }
                }
                if($employeeDetails->shift_3!=null){
                    $shift_3_emp = Shift::where('shift_id',$employeeDetails->shift_3)->first();
                    //$shift_1_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_1_emp->start_time)));
                    $shift_3_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_3_emp->start_time)));
                    $diff_in_minutes_less = $shift_time->diffInMinutes($punch_1);
                    $diff_in_minutes_3 = $shift_3_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_3<0)
                        $diff_in_minutes_3 *= -1;

                    if($diff_in_minutes_less > $diff_in_minutes_3){
                        $shiftDetails =  $shift_3_emp;
                        $shift_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time)));
                    }
                }
                if($employeeDetails->shift_4!=null){
                    $shift_4_emp = Shift::where('shift_id',$employeeDetails->shift_4)->first();
                    $shift_4_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_4_emp->start_time)));
                    $diff_in_minutes_less = $shift_time->diffInMinutes($punch_1);
                    $diff_in_minutes_4 = $shift_4_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_4<0)
                        $diff_in_minutes_4 *= -1;

                    if($diff_in_minutes_less > $diff_in_minutes_4){
                        $shiftDetails =  $shift_4_emp;
                        //$shift_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time)));
                    }
                }
        }   

        $punchToInsert = new Punch([
            'emp_id'=>$employeeDetails->employee_id,
            'branch_id'=>$employeeDetails->branch_id,
            'dept_id'=>$employeeDetails->dept_id,
            'company_id'=>$employeeDetails->company_id,
            'category_id'=>$employeeDetails->category_id,
            'roster_id'=>$req->roster_id,
            'punch_date'=>$req->punch_date,
            'punch_1'=>$req->punch_1,
            'punch_2'=>$req->punch_2,
            'punch_3'=>$req->punch_3,
            'punch_4'=>$req->punch_4,
            'punch_5'=>$req->punch_5,
            'punch_6'=>$req->punch_6,
            'shift_code'=>$shiftDetails->shift_id,
            'half_1_gate_pass'=>$req->half_1_gate_pass,
            'half_2_gate_pass'=>$req->half_2_gate_pass,
            'half_1_gp_out'=>$req->half_1_gp_out,
            'half_1_gp_in'=>$req->half_1_gp_in,
            'half_1_gp_hrs'=>$req->half_1_gp_hrs,
            'half_2_gp_out'=>$req->half_2_gp_out,
            'half_2_gp_in'=>$req->half_2_gp_in,
            'half_2_gp_hrs'=>$req->half_2_gp_hrs,
            'remarks'=>$req->remarks,
            'is_manual_entry_done'=>1
            
            // 'early_in'=>,
                // 'early_out'=>,
                // 'late_in'=>,
                // 'overstay'=>,
                // 'overtime'=>,
                // 'comp_off'=>,
                // 'comp_off_avail'=>,
                // 'hours_worked_minutes'=>,
                // 'half_1'=>,
                // 'half_2'=>,
                // 'final_half_1'=>,
                // 'final_half_2'=>,
            
            // 'deduction_minutes'=>, 
        ]);
        $punchToInsert->status = $req->status;

        $dt_shift_start = new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time));
        $shift_start_carbon = Carbon::instance($dt_shift_start);
        $dt_shift_end = new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->end_time));
        $shift_end_carbon = Carbon::instance($dt_shift_end);
        
        $workedMinutes = 0;

        if($req->punch_1 != null){
            $dt1 = new DateTime($req->punch_1);
            $punch_1_carbon = Carbon::instance($dt1);

            $timeDifference = $shift_start_carbon->diffInMinutes($punch_1_carbon, false);
            if($timeDifference >0){ // late case
                if($timeDifference > $shiftDetails->grace_late){
                    $punchToInsert->late_in = $timeDifference - $shiftDetails->grace_late;
                    $punchToInsert->half_1 = null;
                    $punchToInsert->early_in = null;
                }
                else{
                    $punchToInsert->late_in = null;
                    $punchToInsert->half_1 = 1;
                    $punchToInsert->early_in = null;
                }    
            }else{  // early case
                $punchToInsert->late_in = null;
                $punchToInsert->half_1 = 1;
                $timeDifference *= -1;
                if($timeDifference >$shiftDetails->grace_early)
                    $punchToInsert->early_in = $timeDifference - $shiftDetails->grace_early;
                else    
                    $punchToInsert->early_in = null;
            }
            if($req->punch_2!=null){
                
                $dt2 = new DateTime($req->punch_2);
                $punch_2_carbon = Carbon::instance($dt2);
                $workedMinutes = $punch_2_carbon->diffInMinutes($punch_1_carbon);
 
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_2_carbon, false);
                if($timeDifference > 0){
                    $punchToInsert->half_2 = 1;
                    if($timeDifference >$shiftDetails->grace_late){
                        $punchToInsert->overstay = $timeDifference - $shiftDetails->grace_late;
                        
                        if($employeeDetails->overtime_applicable == 1)
                            $punchToInsert->overtime = $timeDifference;
                        else    
                            $punchToInsert->overtime = null;
                    }else{
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shiftDetails->grace_early){
                        $punchToInsert->half_2 = 0;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                        $punchToInsert->early_out = $timeDifference;
                    }else{
                        $punchToInsert->half_2 = 1;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }
            }
               
            if($req->punch_4!=null){
                $dt3 = new DateTime($req->punch_3);
                $dt4 = new DateTime($req->punch_4);
                $punch_3_carbon = Carbon::instance($dt3);
                $punch_4_carbon = Carbon::instance($dt4);
                $workedMinutes += $punch_4_carbon->diffInMinutes($punch_3_carbon);
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_4_carbon, false);
                if($timeDifference > 0){
                    $punchToInsert->half_2 = 1;
                    if($timeDifference >$shiftDetails->grace_late){
                        $punchToInsert->overstay = $timeDifference - $shiftDetails->grace_late;
                        
                        if($employeeDetails->overtime_applicable == 1)
                            $punchToInsert->overtime = $timeDifference;
                        else    
                            $punchToInsert->overtime = null;
                    }else{
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shiftDetails->grace_early){
                        $punchToInsert->half_2 = 0;
                        $punchToInsert->early_out = $timeDifference;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }else{
                        $punchToInsert->half_2 = 1;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }
            }
            
            if($req->punch_5 != null){
            }
            if($req->punch_6 != null){
                $dt5 = new DateTime($req->punch_5);
                $dt6 = new DateTime($req->punch_6);
                $punch_5_carbon = Carbon::instance($dt5);
                $punch_6_carbon = Carbon::instance($dt6);
                $workedMinutes += $punch_6_carbon->diffInMinutes($punch_5_carbon);
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_6_carbon, false);
                if($timeDifference > 0){
                    $punchToInsert->half_2 = 1;
                    if($timeDifference >$shiftDetails->grace_late){
                        $punchToInsert->overstay = $timeDifference - $shiftDetails->grace_late;
                        
                        if($employeeDetails->overtime_applicable == 1)
                            $punchToInsert->overtime = $timeDifference;
                        else    
                            $punchToInsert->overtime = null;
                    }else{
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shiftDetails->grace_early){
                        $punchToInsert->half_2 = 0;
                        $punchToInsert->early_out = $timeDifference;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }else{
                        $punchToInsert->half_2 = 1;
                        $punchToInsert->overstay = null;
                        $punchToInsert->overtime = null;
                    }
                }
            }
            $punchToInsert->hour_worked_minutes = $workedMinutes;
            $punchToInsert->final_half_1 = $punchToInsert->half_1 == 1 ? "PR" : "AB";
            $punchToInsert->final_half_2 = $punchToInsert->half_2 == 1 ? "PR" : "AB";
            $punchToInsert->is_manual_entry_done = 1;

            $punchToInsert->save();
            $punch = Punch::where('id',$punchToInsert->id)->first();
            $roster = Roster::where([['date',$punch->punch_date],['employee_id',$punch->emp_id]])->first();
            $roster->shift_id = $punch->shift_code;
            $roster->is_holiday = $punch->status;
            $roster->final_half_1 = $punch->final_half_1;
            $roster->final_half_2 = $punch->final_half_2;
            $roster->updated_at = Carbon::now();
            $roster->save();
            return response()->json($punchToInsert);
        }
        return null;
        
    }

    public function getPunchDetails($branch_id,$emp_id,$date){
        $company_id = Session::get('company_id');
        $punchDetail = Punch::where([['company_id',$company_id],['emp_id',$emp_id],['punch_date',$date]])->first();
        if($punchDetail != null)
            return response()->json($punchDetail);
    }
    public function getRosterDetails($branch_id,$emp_id,$date){
        $company_id = Session::get('company_id');
        $rosterDetail = Roster::where([['company_id',$company_id],['branch_id',$branch_id],['employee_id',$emp_id],['date',$date]])->first();
        if($rosterDetail != null){
            return response()->json($rosterDetail);
        }
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

    public function updatePunch(Request $request, $id){
        
        $worked_hours = 0;
        
        $shift = Shift::where('shift_id',$request->shift_id)->first();
        $employeeDetails = Employee::where([['company_id',Session::get('company_id')],['employee_id',$request->employee_id]])->first();
        switch($employeeDetails->auto_shift){
            case null: break;
            case false: 
                $todayRoster = Roster::where('roster_id',$request->roster_id)->first();
                $shift = Shift::where('shift_id',$todayRoster->shift_id)->first();
                break;
            case 1:
            case true: 
                $shift_1_emp = null;
                $shift_2_emp = null;
                $shift_3_emp = null;
                $shift_4_emp = null;
                $shift_1_time = null;
                $shift_2_time = null;
                $shift_3_time = null;
                $shift_4_time = null;
                $dt_punch_1 = new DateTime($request->punch_1);
                $punch_1 = Carbon::instance($dt_punch_1);   
                $shift_time;
                if($employeeDetails->shift_1!= null){
                    $shift_1_emp = Shift::where('shift_id',$employeeDetails->shift_1)->first();
                    $shift =  $shift_1_emp;
                    $shift_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift->start_time)));
                }
                if($employeeDetails->shift_2!=null){
                    $shift_2_emp = Shift::where('shift_id',$employeeDetails->shift_2)->first();
                    $shift_1_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift_1_emp->start_time)));
                    $shift_2_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift_2_emp->start_time)));
                    $diff_in_minutes_1 = $shift_1_time->diffInMinutes($punch_1);
                    $diff_in_minutes_2 = $shift_2_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_1<0)
                        $diff_in_minutes_1 *= -1;
                    if($diff_in_minutes_2<0)
                        $diff_in_minutes_2 *= -1;

                    if($diff_in_minutes_1 > $diff_in_minutes_2){
                        $shift =  $shift_2_emp;
                        $shift_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift->start_time)));
                    }
                }
                if($employeeDetails->shift_3!=null){
                    $shift_3_emp = Shift::where('shift_id',$employeeDetails->shift_3)->first();
                    //$shift_1_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shift_1_emp->start_time)));
                    $shift_3_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift_3_emp->start_time)));
                    $diff_in_minutes_less = $shift_time->diffInMinutes($punch_1);
                    $diff_in_minutes_3 = $shift_3_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_3<0)
                        $diff_in_minutes_3 *= -1;

                    if($diff_in_minutes_less > $diff_in_minutes_3){
                        $shift =  $shift_3_emp;
                        $shift_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift->start_time)));
                    }
                }
                if($employeeDetails->shift_4!=null){
                    $shift_4_emp = Shift::where('shift_id',$employeeDetails->shift_4)->first();
                    $shift_4_time = Carbon::instance(new DateTime($this->makeDateTime($request->punch_date,$shift_4_emp->start_time)));
                    $diff_in_minutes_less = $shift_time->diffInMinutes($punch_1);
                    $diff_in_minutes_4 = $shift_4_time->diffInMinutes($punch_1);
                    if($diff_in_minutes_4<0)
                        $diff_in_minutes_4 *= -1;

                    if($diff_in_minutes_less > $diff_in_minutes_4){
                        $shift =  $shift_4_emp;
                        //$shift_time = Carbon::instance(new DateTime($this->makeDateTime($req->punch_date,$shiftDetails->start_time)));
                    }
                }
        }
        $punch = Punch::where('id',$id)->first();
        //dd( $request->input('punch_1'));
        if($punch != null){
            $employee = Employee::where('employee_id',$punch->emp_id)->first();
            $punch->shift_code = $request->input('shift_id');
            $punch->status = $request->input('status');
            $punch->punch_date = $request->input('punch_date');
            $punch->punch_1 = $request->input('punch_1');

            $dt_shift_start = new DateTime($this->makeDateTime($punch->punch_date,$shift->start_time));
            $shift_start_carbon = Carbon::instance($dt_shift_start);
            $dt_shift_end = new DateTime($this->makeDateTime($punch->punch_date,$shift->end_time));
            $shift_end_carbon = Carbon::instance($dt_shift_end);

            if($punch->punch_1!=null){
                $dt1 = new DateTime($punch->punch_1);
                $punch_1_carbon = Carbon::instance($dt1);
                
                $timeDifference = $shift_start_carbon->diffInMinutes($punch_1_carbon, false);
                if($timeDifference >0){ // late case
                    if($timeDifference > $shift->grace_late){
                        $punch->late_in = $timeDifference - $shift->grace_late;
                        $punch->half_1 = null;
                        $punch->early_in = null;
                    }
                    else{
                        $punch->late_in = null;
                        $punch->half_1 = 1;
                        $punch->early_in = null;
                    }    
                }else{  // early case
                    $punch->late_in = null;
                    $punch->half_1 = 1;
                    $timeDifference *= -1;
                    if($timeDifference >$shift->grace_early)
                        $punch->early_in = $timeDifference - $shift->grace_early;
                    else    
                        $punch->early_in = null;
                }
            }   
            $punch->punch_2 = $request->input('punch_2');
            if($punch->punch_2!=null){
                
                $dt2 = new DateTime($punch->punch_2);
                $punch_2_carbon = Carbon::instance($dt2);
                $worked_hours = $punch_2_carbon->diffInMinutes($punch_1_carbon);
 
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_2_carbon, false);
                if($timeDifference > 0){
                    $punch->half_2 = 1;
                    if($timeDifference >$shift->grace_late){
                        $punch->overstay = $timeDifference - $shift->grace_late;
                        
                        if($employee->overtime_applicable == 1)
                            $punch->overtime = $timeDifference;
                        else    
                            $punch->overtime = null;
                    }else{
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shift->grace_early){
                        $punch->half_2 = 0;
                        $punch->early_out = $timeDifference;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }else{
                        $punch->half_2 = 1;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }
            }
            
            $punch->punch_3 = $request->input('punch_3');
            $punch->punch_4 = $request->input('punch_4');    
            if($punch->punch_4!=null){
                $dt3 = new DateTime($punch->punch_3);
                $dt4 = new DateTime($punch->punch_4);
                $punch_3_carbon = Carbon::instance($dt3);
                $punch_4_carbon = Carbon::instance($dt4);
                $worked_hours += $punch_4_carbon->diffInMinutes($punch_3_carbon);
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_4_carbon, false);
                if($timeDifference > 0){
                    $punch->half_2 = 1;
                    if($timeDifference >$shift->grace_late){
                        $punch->overstay = $timeDifference - $shift->grace_late;
                        
                        if($employee->overtime_applicable == 1)
                            $punch->overtime = $timeDifference;
                        else    
                            $punch->overtime = null;
                    }else{
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shift->grace_early){
                        $punch->half_2 = 0;
                        $punch->early_out = $timeDifference;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }else{
                        $punch->half_2 = 1;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }
            }
            $punch->punch_5 = $request->input('punch_5');
            if($request->input('punch_5')!=null){
            }
            $punch->punch_6 = $request->input('punch_6');
            if($request->input('punch_6')!=null){
                $dt5 = new DateTime($punch->punch_5);
                $dt6 = new DateTime($punch->punch_6);
                $punch_5_carbon = Carbon::instance($dt5);
                $punch_6_carbon = Carbon::instance($dt6);
                $worked_hours += $punch_6_carbon->diffInMinutes($punch_5_carbon);
                $timeDifference = $shift_end_carbon->diffInMinutes($punch_6_carbon, false);
                if($timeDifference > 0){
                    $punch->half_2 = 1;
                    if($timeDifference >$shift->grace_late){
                        $punch->overstay = $timeDifference - $shift->grace_late;
                        
                        if($employee->overtime_applicable == 1)
                            $punch->overtime = $timeDifference;
                        else    
                            $punch->overtime = null;
                    }else{
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }else{
                    $timeDifference *= -1;
                    if($timeDifference > $shift->grace_early){
                        $punch->half_2 = 0;
                        $punch->early_out = $timeDifference;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }else{
                        $punch->half_2 = 1;
                        $punch->overstay = null;
                        $punch->overtime = null;
                    }
                }
            }
            $punch->shift_code = $shift->shift_id;
            $punch->hour_worked_minutes = $worked_hours;
            $punch->half_1_gate_pass = $request->input('half_1_gate_pass');
            $punch->half_1_gp_out = $request->input('half_1_gp_out');
            $punch->half_1_gp_in = $request->input('half_1_gp_in');
            $punch->half_1_gp_hrs = $request->input('half_1_gp_hrs');
            $punch->half_2_gate_pass = $request->input('half_2_gate_pass');
            $punch->half_2_gp_out = $request->input('half_2_gp_out');
            $punch->half_2_gp_in = $request->input('half_2_gp_in');
            $punch->half_2_gp_hrs = $request->input('half_2_gp_hrs');
            $punch->remarks = $request->input('remarks');
            $punch->final_half_1 = $punch->half_1 == 1 ? "PR" : "AB";
            $punch->final_half_2 = $punch->half_2 == 1 ? "PR" : "AB";
            $punch->is_manual_entry_done = 1;
            $punch->updated_at = Carbon::now();
            $punch->save();
            $punch = Punch::where('id',$id)->first();
            $roster = Roster::where([['date',$punch->punch_date],['employee_id',$employee->employee_id]])->first();
            $roster->shift_id = $punch->shift_code;
            $roster->is_holiday = $punch->status;
            $roster->final_half_1 = $punch->final_half_1;
            $roster->final_half_2 = $punch->final_half_2;
            $roster->updated_at = Carbon::now();
            $roster->save();
                
            return response()->json($punch);
            
        }

    }
    public function deletePunch($id){
        $punch = Punch::where('id',$id)->delete();
        return response()->json($punch);
    }

    public function getLeaveRequests(){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        $appliedLeaves = AppliedLeave::with('employee','leave')->get();
        $companyLeaves = CompanyLeave::where('company_id',$company_id)->with('leaveMaster')->get();
        return view('pages/admin/leave/appliedLeaves/employeeLeaves',['appliedLeaves'=>$appliedLeaves, 'companyLeaves'=>$companyLeaves, 'companyBranches'=>$branches]);
    }
 
    public function getEmployeeLeaveDetails($emp_id){
        $company_id = Session::get('company_id');
        $employee = Employee::where([['company_id',$company_id],['employee_id',$emp_id]])->first();
        $employeeLeaves = LeaveQuota::where([['company_id',$company_id],['employee_id',$employee->employee_id]])->with('leaveMaster')->get();
        return $employeeLeaves;
    }

    public function getEmployeeLeaveStatus($eId, $lId){
        $appliedLeaves = LeaveQuota::where([['employee_id',$eId],['leave_id',$lId]])->first();
        $leaveDetail = LeaveMaster::where('leave_id',$lId)->first();
        $dataToSend = [
            'used'=>$appliedLeaves->used_days,
            'total'=>$appliedLeaves->alloted_days
        ];
        return $dataToSend;
    }

    public function applyLeaveOfEmployee(Request $req){
        $company_id = Session::get('company_id');
        $new = new AppliedLeave([
            'company_id'=>$company_id,
            'emp_id'=>$req->emp_id,
            'leave_id'=>$req->leave_id,
            'applied_days'=>$req->applied_days,
            'posted_days'=>$req->posted_days,
            'leave_from'=>$req->leave_from,
            'leave_to'=>$req->leave_to,
            'day_part'=>$req->day_part,
            'comp_off_date_1'=>$req->comp_off_date_1,
            'comp_off_date_2'=>$req->comp_off_date_2,
            'remarks'=>$req->remarks,
            'status'=>$req->status,
            'approved_by'=>$req->approved_by,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now() 
        ]);
        $new->save();

        $updateQuota = LeaveQuota::where([['employee_id',$new->emp_id],['leave_id',$new->leave_id]])->first();
        if($new->day_part ==3)
            $updateQuota->used_days = $updateQuota->used_days + $new->posted_days;
        else
            $updateQuota->used_days = $updateQuota->used_days + $new->posted_days/2;
        $updateQuota->save();
        
        $dataSaved = AppliedLeave::where('id',$new->id)->with('employee','leave')->first();
        return response()->json($dataSaved);
    }
    public function deleteAppliedLeave($id){
        $appliedLeave = AppliedLeave::where('id',$id)->delete();
        return response()->json($appliedLeave);
    }
    public function getAppliedLeaveById($id){
        $company_id = Session::get('company_id');
        $found = AppliedLeave::where('id',$id)->first();
        $leave = LeaveMaster::where('leave_id',$found->leave_id)->first();
        $employee = Employee::where('employee_id',$found->employee->employee_id)->with('branch')->first();
        $companyLeaves = CompanyLeave::where([['company_id',$company_id],['branch_id',$employee->branch_id]])->with('leaveMaster')->get();
        
        $dataToSend = [
            'companyLeaves'=>$companyLeaves,
            'branch_name'=>$employee->branch->name,
            'emp_id'=>$employee->employee_id,
            'emp_name'=>$employee->name,
            'leave_id'=>$found->leave_id,
            'leave_days'=>$found->posted_days,
            'day_part'=>$found->day_part,
            'leave_from'=>$found->leave_from,
            'leave_to'=>$found->leave_to,
            'remarks'=>$found->remarks,
            'leaveDetail'=>$leave
        ];
        return response()->json($dataToSend);
    }
    public function updateAppliedLeave(Request $req){
        $leaveToUpdate = AppliedLeave::where('id',$req->id)->first();
        $leaveToUpdate->leave_id = $req->leave_id;
        $leaveToUpdate->applied_days = $req->applied_days;
        $leaveToUpdate->posted_days = $req->posted_days;
        $leaveToUpdate->leave_from = $req->leave_from;
        $leaveToUpdate->leave_to = $req->leave_to ;
        $leaveToUpdate->day_part = $req->day_part ;
        $leaveToUpdate->remarks = $req->remarks ;
        $leaveToUpdate->updated_at = Carbon::now() ;
        $leaveToUpdate->save();
        
        $dataUpdated = AppliedLeave::where('id',$req->id)->with('employee','leave')->first();

        $userAppliedLeaves = AppliedLeave::where([['emp_id',$dataUpdated->emp_id],['leave_id',$dataUpdated->leave_id]])->get();
        $usedDays = 0;
        foreach($userAppliedLeaves as $leave){
            $usedDays += $leave->posted_days;
        }
        $updateQuota = LeaveQuota::where([['employee_id',$dataUpdated->emp_id],['leave_id',$dataUpdated->leave_id]])->first();
        $updateQuota->used_days = $usedDays;
        $updateQuota->save();

        return response()->json($dataUpdated);

    }
    
}
