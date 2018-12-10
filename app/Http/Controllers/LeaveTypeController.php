<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveTypes;
use App\LeaveMaster;
use App\Branch;
use App\CompanyLeave;
use Session;

class LeaveTypeController extends Controller
{
    public function addLeaveTypes(){
        $comp_id = Session::get('company_id');
        $branches= Branch::where('company_id',$comp_id)->get('branch_id','name');
        $leaves = LeaveMaster::where('company_id',$comp_id)->get('branch_id','name');
        $companyLeave = CompanyLeave::where('company_id',$comp_id)->get();
        $dataToDisplay =[];
        foreach($companyLeave as $cl){
            $branch = Branch::where('branch_id',$cl->branch_id)->get('branch_id','name');
            $leave = LeaveMaster::where('leave_id',$cl->leave_id)->get('leave_id','name');
            $data = [
                'branch_id'=>$branch->branch_id,
                'branch_name'=>$branch->name,
                'leave_id'=>$leave->leave_id,
                'leave_name'=>$leave->name
            ];
            $dataToDisplay += $data;
        }
        //print_r($dataToDisplay);
        
        return view('pages/admin/leave/types/viewLeaveTypes',['branches'=>$branches,'leaves'=>$leaves, 'companyLeave'=>$companyLeave, 'dataToDisplay'=>$dataToDisplay]);
    }
    public function getLeaveTypes(){
        $comp_id = (String)Session::get('company_id');
        
        $branches= Branch::where('company_id',$comp_id)->get();
        $leaves = LeaveMaster::where('company_id',$comp_id)->get();
        $companyLeave = CompanyLeave::where('company_id',$comp_id)->get();
        $dataToDisplay[] =[];
        $index =0;
        foreach($companyLeave as $cl){
            $branch = Branch::where('branch_id',$cl->branch_id)->first();
            $leave = LeaveMaster::where('leave_id',$cl->leave_id)->first();
            $dataToDisplay[$index]=[
                'branch_id'=>$branch->branch_id,
                'branch_name'=>$branch->name,
                'leave_id'=>$leave->leave_id,
                'leave_name'=>$leave->name
            ];
            $index++;
        }
        if(count($companyLeave)>0)
            return view('pages/admin/leave/types/viewLeaveTypes',['branches'=>$branches,'leaves'=>$leaves,'dataToDisplay'=>$dataToDisplay]);
        else{
            return view('pages/admin/leave/types/viewLeaveTypes',['branches'=>$branches,'leaves'=>$leaves,'dataToDisplay'=>[]]);
        }
    }
    public function getLeaveTypeById($leave_id, $branch_id){
        
        $leaveType = CompanyLeave::where([['leave_id',$leave_id],['branch_id',$branch_id]])->first();
        $leaveDetail = LeaveMaster::where('leave_id',$leaveType->leave_id)->first();
        $branchDetail = Branch::where('branch_id',$leaveType->branch_id)->first();
        $dataToDisplay = [
            'leave_id'=>$leaveDetail->leave_id,
            'leave_name'=>$leaveDetail->name,
            'branch_id'=>$branchDetail->branch_id,
            'branch_name'=>$branchDetail->name
        ];
        return $dataToDisplay;
    }
    public function addLeaveToBranch(Request $request){
        $compLeave = CompanyLeave::create($request->input());
        $dataWithNames = [
            'data'=> $compLeave,
            'names'=>[
                'branch_name'=> Branch::where('branch_id',$request->input('branch_id'))->pluck('name')->first(),
                'leave_name'=> LeaveMaster::where('leave_id',$request->input('leave_id'))->pluck('name')->first()
            ]
        ];
        return response()->json($dataWithNames);
    }
    public function deleteLeaveType($leave_id,$branch_id){
        $company_id = Session::get('company_id');
        $leave = CompanyLeave::where([['company_id',$company_id],['leave_id',$leave_id],['branch_id',$branch_id]])->delete();
        return response()->json($leave);
    }
    public function updateLeaveType(Request $request, $leave_id,$branch_id){
        $company_id = Session::get('company_id');
        $update = CompanyLeave::where([['company_id',$company_id],['leave_id',$leave_id],['branch_id',$branch_id]])->first();
        $update->leave_id = $request->leave_id;
        $update->branch_id = $request->branch_id;
        $update->save();
        $dataWithNames = [
            'data'=> $update,
            'names'=>[
                'branch_name'=> Branch::where('branch_id',$request->input('branch_id'))->pluck('name')->first(),
                'leave_name'=> LeaveMaster::where('leave_id',$request->input('leave_id'))->pluck('name')->first()
            ]
        ];
        return response()->json($dataWithNames);
    }

    public function getBranchLeaves($branch_id){
        $comp_id = Session::get('company_id');
        
        $leaveTypes = CompanyLeave::where([['company_id',$comp_id],['branch_id',$branch_id]])->with('leaveMaster')->get();
        $dataToSend = [];
        foreach($leaveTypes as $leave){
            $data = ['leave_id'=>$leave->leave_id, 'leave_name'=>$leave->leaveMaster->name];
            array_push($dataToSend, $data);
        }
        return $dataToSend;
    }
}
