<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use datatables;
use App\User;
use Session;
use App\Branch;
use App\Category;
use App\Designation;
use App\Department;
use App\Company;
use App\Employee;
use App\Shift;
use App\Student;
use App\Roster;

class EmployeeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatables::of(User::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees');
    }
    public function getAllEmployeesOfCompany(){
        $company_id = Session::get('company_id');
        $employees = Employee::where('company_id',$company_id)->with(['designation'=>function($query){
            $query->pluck('name');
        }])->get();
        
        return response()->json($employees);
    }

    public function getEmployees(){
        $comp_id = Session::get('company_id');
        $branches = Branch::where('company_id',$comp_id)->get();
        $categories = Category::where('company_id',$comp_id)->get();
        $shifts = Shift::where('company_id',$comp_id)->get();
        $companies = Company::all();
        $designations = Designation::where('company_id',$comp_id)->get();
        $employees = Employee::where('company_id',$comp_id)->with('department')->with('designation','branch','first_shift')->get();
        
        $departments = Department::where('company_id',$comp_id)->get();
        return view('pages/admin/employee/viewEmployees',['employees'=>$employees,'branches'=>$branches, 'companies'=>$companies,'categories'=>$categories,'shifts'=>$shifts ,'designations'=>$designations, 'departments'=>$departments]);
    }

    public function getEmployeeById($id){
        $comp_id = Session::get('company_id');
        $employee = Employee::where([['employee_id',$id],['company_id',$comp_id]])->first();
        return $employee;
    }
    public function getEmployeesByBranch($id){
        $comp_id = Session::get('company_id');
        $employee = Employee::where([['branch_id',$id],['company_id',$comp_id]])->get();
        return $employee;
    }
    public function updateEmployee(Request $request, $id){
        $comp_id = Session::get('company_id');
        $e = Employee::where([['company_id',$comp_id],['employee_id',$id]])->first();
        if($e->email == null && $request->email != null){
            $user = User::create([
                'company_id'        =>  $request->company_id,
                'employee_id'       =>  $request->employee_id,
                'name'              =>  $request->name,
                'email'             =>  $request->email,
                'role'              =>  'employee',
                'password'          =>  bcrypt('test@123'),
                'added_by'          =>  Session::get('user_id'),
                'password_changed'  =>  0
            ]);
            $user->save();
        }else if($e->email != $request->email){
            $findUser = User::where([['company_id',$request->company_id],['employee_id',$request->employee_id]])->first();
            
        }
        $e->employee_id = $request->employee_id;
        $e->name = $request->name;
        $e->mobileNumber1 = $request->mobileNumber1;
        $e->mobileNumber2 = $request->mobileNumber2;
        $e->email = $request->email;
        $e->country = $request->country;
        $e->state = $request->state;
        $e->city = $request->city;
        $e->street_address_1 = $request->street_address_1;
        $e->street_address_2 = $request->street_address_2;
        $e->postal_code = $request->postal_code;
        $e->dob = $request->dob;
        $e->gender = $request->gender;
        $e->marital_status = $request->marital_status;
        $e->anniversary = $request->anniversary;
        $e->father_name = $request->father_name;
        $e->educational_qualification = $request->educational_qualification;
        $e->professional_qualification = $request->professional_qualification;
        $e->experience = $request->experience;
        $e->card_number = $request->card_number;
        $e->dept_id = $request->dept_id;
        $e->category_id = $request->category_id;
        $e->branch_id = $request->branch_id;
        $e->designation_id = $request->designation_id;
        $e->Permanent_Temporary = $request->Permanent_Temporary;
        $e->week_off_day = $request->week_off_day;
        $e->additional_off_day = $request->additional_off_day;
        $e->additional_off_week = $request->additional_off_week;
        $e->shift_1 = $request->shift_1;
        $e->shift_2 = $request->shift_2;
        $e->shift_3 = $request->shift_3;
        $e->shift_4 = $request->shift_4;
        $e->auto_shift =$request->auto_shift;
        $e->change_by_week = $request->change_by_week;
        $e->change_after_days = $request->change_after_days;
        $e->changed_on_day = $request->changed_on_day;
        $e->half_day_shift = $request->half_day_shift;
        $e->half_day_on = $request->half_day_on;
        $e->comp_off_applicable = $request->comp_off_applicable;
        $e->overtime_applicable = $request->overtime_applicable;
        $e->reporting_officer_1 = $request->reporting_officer_1;
        $e->reporting_officer_2 = $request->reporting_officer_2;
        $e->joining_date = $request->joining_date;
        $e->leaving_date = $request->leaving_date;
        $e->referred_by = $request->referred_by;
        $e->ESI_number = $request->ESI_number;
        $e->PF_number = $request->PF_number;
        $e->UAN_number = $request->UAN_number;
        $e->PAN_number = $request->PAN_number;
        $e->wage_type = $request->wage_type;
        $e->bank_name = $request->bank_name;
        $e->IFSC_code = $request->IFSC_code;
        $e->bank_branch = $request->bank_branch;
        $e->bank_account_number = $request->bank_account_number;
        if($e->save()){
            $dataToSend = [
                'orig_data'=>$e,
                'branch_name'=>Branch::where([['branch_id',$e->branch_id],['company_id',$e->company_id]])->pluck('name'),
                'department_name'=>Department::where([['department_id',$e->dept_id],['company_id',$e->company_id]])->pluck('name'),
                'designation_name'=>Designation::where([['designation_id',$e->designation_id],['company_id',$e->company_id]])->pluck('name'),
                'shift_name'=>Shift::where([['shift_id',$e->shift_1],['company_id',$e->company_id]])->pluck('name')->first()
            ];
            return response()->json($dataToSend);
        }
    }
    public function deleteEmployee($id){
        $employee = Employee::where('employee_id',$id)->delete();
        //$user = User::where('email',$employee->email)->delete();
        return response()->json($employee);
    }
    public function addEmployee(Request $request){
        $new  = Employee::create($request->input());
        
        if($request->email != null){
            $user = User::create([
                'company_id'    => $new->company_id,
                'employee_id'   => $request->employee_id,
                'name'          => $new->name,
                'email'         => $new->email,
                'role'          => 'employee',
                'password'      => bcrypt('test@123'),
                'added_by'      => Session::get('user_id'),
                'password_changed'=> 0
            ]);
            $user->save();
        }
        $dataToSend = [
            'orig_data'=>$new,
            'branch_name'=>Branch::where([['branch_id',$new->branch_id],['company_id',$new->company_id]])->pluck('name'),
            'department_name'=>Department::where([['department_id',$new->dept_id],['company_id',$new->company_id]])->pluck('name'),
            'designation_name'=>Designation::where([['designation_id',$new->designation_id],['company_id',$new->company_id]])->pluck('name'),
            'shift_name'=>Shift::where([['shift_id',$new->shift_1],['company_id',$new->company_id]])->pluck('name')->first()
        ];
        return response()->json($dataToSend);
        
    }
    public function findCardNumber($number){
        $findEmployee = Employee::where([['company_id',Session::get('company_id')],['card_number',$number]])->first();
        $findStudent = Student::where([['institution_id',Session::get('company_id')],['card_number',$number]])->first();
        if($findEmployee!=null || $findStudent !=null){
            $message = "duplicate";
        }else{
            $message = "no-duplicate";
        }
        return $message;
    }
    public function getEmployeeMonthlyLogDetails($month,$year){
        $startDate = $year .'-'.$month.'-01';
        $endDate = $year.'-'.$month.'-31';
        $employeeRosters = Roster::where([['company_id',Session::get('company_id')],['employee_id',Session::get('user_id')]])
                                ->whereBetween('date',[$startDate,$endDate])
                                ->with('shift')->get();
        return response()->json($employeeRosters);
    }


}
