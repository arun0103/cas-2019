<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holiday;
use App\Roster;
use App\Employee;
use Carbon\Carbon;
use Session;
use Response;

class HolidayController extends Controller
{
    public function getHolidays(){
        $holidays = Holiday::where('company_id', Session::get('company_id'))->get();
        $dataToSend = [];
        $index = 0;
        foreach($holidays as $holiday){
            $data = [
                'id'=>$holiday->id,
                'title'=> $holiday->holiday_description,
                'start'=> $holiday->holiday_date,
                'end'=> $holiday->holiday_date,
                'allDay'=> 1,
                'color'=>'red'
            ];
            $dataToSend[$index]=$data;
            $index++;

        }
        return response()->json($dataToSend);
    }

    public function addHoliday(Request $request){
        $new = new Holiday([
            'holiday_description'=>$request->holiday_description,
            'holiday_date'=>$request->holiday_date,
            'company_id'=>$request->company_id,
            'branch_id'=>$request->branch_id
        ]);
        $new->save();
        $rosters = Roster::where([['company_id',$new->company_id],['date',$new->holiday_date]])->get();
        foreach($rosters as $roster){
            $roster->is_holiday = 'H';
            $roster->save();
        }
        $data = [
            'id'=>$new->id,
                'title'=> $new->holiday_description,
                'start'=> $new->holiday_date,
                'end'=> $new->holiday_date,
                'allDay'=> 1,
                'color'=>'red',
                'description'=>"holiday",
                'editable'=>true,
                'clickable'=>true 
        ];
        return response()->json($data);
    }
    public function deleteHoliday($id){
        $del = Holiday::where('id',$id)->first();
        
        $rosters = Roster::where([['company_id',$del->company_id],['date',$del->holiday_date]])->get();
        foreach($rosters as $roster){
            $empDetail = Employee::where([['company_id',$roster->company_id],['employee_id',$roster->employee_id]])->first();
            $carbon_date = new Carbon($roster->date);
            if($empDetail->week_off_day == $carbon_date->dayOfWeek)
                $roster->is_holiday = 'O';
            else if($empDetail->additional_off_day != null && $empDetail->additional_off_week != null){
                if($mepDetail->additional_off_day == $carbon_date->dayOfWeek){
                    $weeks = explode( ',', $empDetail->additional_off_week);
                    foreach($weeks as $week){
                        if($week == $carbon_date->weekOfMonth){
                            $roster->is_holiday = 'O';
                        }
                    }
                }
            }
            else
                $roster->is_holiday = 'W';
            $roster->save();
        }
        $del->delete();
        return response()->json($del);
    }
}
