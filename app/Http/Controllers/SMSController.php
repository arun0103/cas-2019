<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SMSReport;

class SMSController extends Controller
{
    public function postDeliveryStatus(){
        $request = $_REQUEST["data"];
        $jsonData = json_decode($request,true);
        foreach($jsonData as $key => $value)
        {
            // request id
            $requestID = $value['requestId'];
            $userId = $value['userId'];
            $senderId = $value['senderId'];
            foreach($value['report'] as $key1 => $value1)
            {
                //detail description of report
                $desc = $value1['desc'];
                // status of each number
                $status = $value1['status'];
                // destination number
                $receiver = $value['number'];
                //delivery report time
                $date = $value1['date'];
                
                $report = new SMSReport([
                    'requestId'=>$requestID,
                    'userId'=>$userId,
                    'senderId'=>$senderId,
                    'desc'=>$desc,
                    'number'=>$receiver,
                    'date'=>$date,
                    'status'=>$status
                ]);
                $report->save();
            }
        }
        return 'success';
    }
}
