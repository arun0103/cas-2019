<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveMaster;
use Session;

class LeaveMasterController extends Controller
{
    public function addLeaveMaster(Request $request){
        $leaveMaster = LeaveMaster::create($request->input());
        return response()->json($leaveMaster);
        // $leaveMaster = new LeaveMaster([
        //     'leave_id'=>request('leave_id'),
        //     'name'=>request('leave_name'),
        //     'max_leave_allowed'=>request('maxLeaveAllowed'),
        //     'min_leave_allowed'=>request('minLeaveAllowed'),
        //     'weekly_off_cover'=>request('weekly_off_cover'),
        //     'paid_holiday_cover'=>request('paid_holiday_cover'),
        //     'club_with_leaves'=>implode(',',request('selectedClubWith')),
        //     'cant_club_with_leaves'=>implode(',',request('selectedCannotClubWith')),
        //     'balance_adjusted_from'=>request('balanceAdjustedFrom'),
        //     'treat_present'=>request('treat_present'),
        //     'treat_absent'=>request('treat_absent'),
        // ]);
        // if($leaveMaster->save()){
        //     return redirect('/admin/leave/master/add')->with('status','Leave Master created!');
        // }
    }
    public function updateLeaveMaster(Request $request,$id){
        $update = LeaveMaster::where('leave_id', $id)->first();
        $update->leave_id = $request->leave_id;
        $update->name = $request->name;
        $update->max_leave_allowed = $request->max_leave_allowed;
        $update->min_leave_allowed = $request->min_leave_allowed;
        $update->weekly_off_cover = $request->weekly_off_cover;
        $update->paid_holiday_cover = $request->paid_holiday_cover;
        $update->club_with_leaves = $request->club_with_leaves;
        $update->balance_adjusted_from = $request->balance_adjusted_from;
        $update->treat_present = $request->treat_present;
        $update->treat_absent = $request->treat_absent;
        $update->save();
        return response()->json($update);

    }
    public function getLeaveMaster(){
        $company_id = Session::get('company_id');
        $leaveMaster = LeaveMaster::where('company_id',$company_id)->get();
        return view('pages/admin/leave/master/viewLeaveMaster', ['leaveMaster'=>$leaveMaster,'allLeaves'=>$leaveMaster]);
    }
    public function getDataForLeaveMaster(){
        $company_id = Session::get('company_id');
        $leaveMaster = LeaveMaster::where('company_id',$company_id)->get();
        return view('pages/admin/leave/master/addLeaveMaster', ['allLeaves'=>$leaveMaster]);
    }

    public function getLeaveMasterById($id){
        $leaveMaster = LeaveMaster::where('leave_id',$id)->first();
        return $leaveMaster;
    }
    public function deleteLeaveMaster($id){
        $company_id = Session::get('company_id');
        $leaveMaster = LeaveMaster::where([['company_id',$company_id],['leave_id',$id]])->delete();
        return response()->json($leaveMaster);
        
    }
}
