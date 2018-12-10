<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Employee;
use App\Branch;
use App\LeaveTypes;
use App\LeaveQuota;

class LeaveQuotaController extends Controller
{
    public function getAllEmployeesLeaveQuota(){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        $leaves = LeaveTypes::where('company_id',$company_id)->with('leaveDetail')->get();
        $leaveQuotas = LeaveQuota::where('company_id',$company_id)->with('employee','branch','leaveMaster')->get();
        return view('pages/admin/leave/quota/leaves-quota',['branches'=>$branches,'leaves'=>$leaves, 'leaveQuotas'=>$leaveQuotas]);
    }
    public function addLeaveQuota(Request $req){
        $count = 0;
        $total = count($req->employees);
        $data = [];
       foreach($req->employees as $emp){
           $find = LeaveQuota::where([['company_id',$req->company_id],['branch_id',$req->branch_id],['employee_id',$emp],['leave_id',$req->leave_id]])->get();
           if(count($find)==0){
                $new = new LeaveQuota([
                    'company_id'=>$req->company_id,
                    'branch_id'=>$req->branch_id,
                    'employee_id'=>$emp,
                    'leave_id'=>$req->leave_id,
                    'alloted_days'=>$req->alloted_days,
                    'used_days'=>0
                ]);
                if($new->save()){
                    $count++;
                    $newData = LeaveQuota::where('id',$new->id)->with('employee','branch','leaveMaster')->first();
                    array_push($data,$newData);
                }
            }
       }
       $dataToSend = [
           'success'=>$count,
           'total'=>$total,
           'data'=>$data
       ];
       return response()->json($dataToSend);
    }
    public function getLeaveQuotaById($id){
        return LeaveQuota::where('id',$id)->with('branch','employee','leaveMaster')->first();
    }
    public function updateLeaveQuota($id, Request $req){
        $find = LeaveQuota::where('id',$id)->with('branch','employee','leaveMaster')->first();
        $find->leave_id = $req->leave_id;
        $find->alloted_days = $req->alloted_days;
        $find->save();
        return $find;
    }
    public function deleteLeaveQuota($id){
        $delete = LeaveQuota::where('id',$id)->delete();
        return $delete;
    }

}
