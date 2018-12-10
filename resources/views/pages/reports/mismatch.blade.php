<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mismatch Report</title>
    <style>
        @page { margin: 10px; }
        body{
            margin:10px;
        }
        table{
            border: 1px solid black;
            margin: 0 auto;
            text-align: center;
        }
        thead{
            border: 1px solid blue;
        }
        
        thead td{
            border:1px solid black;
            font-weight: bold;
            font-size:14px;
        }
        tbody{
            font-size:8px;
        }
        tbody tr{
            font-size:10px;
        }
        caption{
            font-weight:bold;
            font-size:20px;
        }
        .employee_details{
            font-size:14px;
            font-weight: bolder;
            text-align: left;
            text-decoration:underline;
        }
    </style>
</head>
<body>
    <table>
        <caption><h2><u>Core Time Solution</u></h2></caption>
        <caption>Mismatch Report</caption>
        <thead>
            <tr>
                <td rowspan="2">Date</td>
                <td rowspan="2">Punch1</td>
                <td rowspan="2">Punch2</td>
                <td rowspan="2">Punch3</td>
                <td rowspan="2">Punch4</td>
                <td rowspan="2">Punch5</td>
                <td rowspan="2">Punch6</td>
                <td rowspan="2">Working Hrs.</td>
                <td rowspan="2">Shift Name</td>
                <td rowspan="2">Early In</td>
                <td rowspan="2">Late In</td>
                <td rowspan="2">Early Out</td>
                <td rowspan="2">Overstay</td>
                <td rowspan="2">OT</td>
                <td colspan="2">Punch Status</td>
                <td colspan="2">Authorization</td>
                <td colspan="2">Muster</td>
            </tr>
            <tr>
                <td>1st Half</td>
                <td>2nd Half</td>
                <td>1st Half</td>
                <td>2nd Half</td>
                <td>1st Half</td>
                <td>2nd Half</td>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                @if(count($employee->punch_records) != 0)
                    <tr class="employee_details">
                        <td colspan="5">Emp.Code : {{$employee->employee_id}}</td>
                        <td colspan="5">Emp.Name : {{$employee->name}}</td>
                        <td colspan="5">Designation : {{$employee->designation->name}}</td>
                    </tr>
                    @foreach($employee->punch_records as $punch)
                        <tr>
                            <td>{{substr($punch->punch_date,8,2)}}/{{substr($punch->punch_date,5,2)}}/{{substr($punch->punch_date,0,4)}}</td>
                            <td>{{substr($punch->punch_1,10,6)}}</td>
                            <td>{{substr($punch->punch_2,10,6)}}</td>
                            <td>{{substr($punch->punch_3,10,6)}}</td>
                            <td>{{substr($punch->punch_4,10,6)}}</td>
                            <td>{{substr($punch->punch_5,10,6)}}</td>
                            <td>{{substr($punch->punch_6,10,6)}}</td>
                            <td>
                            @if($punch->hour_worked_minutes != null)
                                @if($punch->hour_worked_minutes/60>9)
                                    {{$punch->hour_worked_minutes/60}}:
                                    @if($punch->hour_worked_minutes%60>9)
                                        {{$punch->hour_worked_minutes%60}}
                                    @else
                                        0{{$punch->hour_worked_minutes%60}}
                                    @endif
                                @else
                                    0{{$punch->hour_worked_minutes/60}}:
                                    @if($punch->hour_worked_minutes%60>9)
                                        {{$punch->hour_worked_minutes%60}}
                                    @else
                                        0{{$punch->hour_worked_minutes%60}}
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>{{$punch->shift->name}}</td>
                            <td>
                            @if($punch->early_in != null)
                                @if($punch->early_in/60>9)
                                    {{floor($punch->early_in/60)}}:
                                    @if($punch->early_in%60>9)
                                        {{$punch->early_in%60}}
                                    @else
                                        0{{$punch->early_in%60}}
                                    @endif
                                @else
                                    0{{floor($punch->early_in/60)}}:
                                    @if($punch->early_in%60>9)
                                        {{$punch->early_in%60}}
                                    @else
                                        0{{$punch->early_in%60}}
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>
                            @if($punch->late_in != null)
                                @if($punch->late_in/60>9)
                                    {{floor($punch->late_in/60)}}:
                                    @if($punch->late_in%60>9)
                                        {{$punch->late_in%60}}
                                    @else
                                        0{{$punch->late_in%60}}
                                    @endif
                                @else
                                    0{{floor($punch->late_in/60)}}:
                                    @if($punch->late_in%60>9)
                                        {{$punch->late_in%60}}
                                    @else
                                        0{{$punch->late_in%60}}
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>
                            @if($punch->early_out != null)
                                @if($punch->early_out/60>9)
                                    {{floor($punch->early_out/60)}}:
                                    @if($punch->early_out%60>9)
                                        {{$punch->early_out%60}}
                                    @else
                                        0{{$punch->early_out%60}}
                                    @endif
                                @else
                                    0{{floor($punch->early_out/60)}}:
                                    @if($punch->early_out%60>9)
                                        {{$punch->early_out%60}}
                                    @else
                                        0{{$punch->early_out%60}}
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>
                            @if($punch->overstay != null)
                                @if($punch->overstay/60>9)
                                    {{floor($punch->overstay/60)}}:
                                    @if($punch->overstay%60>9)
                                        {{$punch->overstay%60}}
                                    @else
                                        0{{$punch->overstay%60}}
                                    @endif
                                @else
                                    0{{floor($punch->overstay/60)}}:
                                    @if($punch->overstay%60>9)
                                        {{$punch->overstay%60}}
                                    @else
                                        0{{$punch->overstay%60}}
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>
                            @if($punch->overtime != null)
                                @if($punch->overtime/60>9)
                                    {{floor($punch->overtime/60)}}:
                                    @if($punch->overtime%60>9)
                                        {{$punch->overtime%60}}
                                    @else
                                        0{{$punch->overtime%60}}
                                    @endif
                                @else
                                    0{{floor($punch->overtime/60)}}:
                                    @if($punch->overtime%60>9)
                                        {{$punch->overtime%60}}
                                    @else
                                        0{{$punch->overtime%60}}
                                    @endif
                                @endif  
                            @endif  
                            </td>
                            <td>{{$punch->final_half_1}}</td>
                            <td>{{$punch->final_half_2}}</td>
                            <td>-19-</td>
                            <td>-20-</td>
                            <td>-21-</td>
                            <td>-22-</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    <script type="text/php">
    if (isset($pdf)) {
        $x = 800;
        $y = 10;
        $text = "{PAGE_NUM}/{PAGE_COUNT}";
        $font = null;
        $size = 14;
        $color = array(255,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
</body>
</html>