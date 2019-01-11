@extends('layouts.master')
@section('head')

@endsection
@section('content')
    <h1>You have not been assigned any roster. Please contact Admin </h1>
@endsection
@section('footer')
<script src="{{asset('js/plugins/jquery/MonthPicker.js')}}"></script>
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script>
  $(document).ready(function(){
    
    $('#no_roster_info').hide();
    $('#table_roster').hide(); 
    $('#txtRosterDate').text("")     
    $('.loading').hide();

    //roster_table = $('#table_roster').DataTable({
      // 'paging'        : true,
      // 'lengthChange'  : true,
      // 'searching'     : true,
      // 'ordering'      : true,
      // 'info'          : true,
      // 'autoWidth'     : true,
      // 'scrollX'       : true
    //});

    $('#txtRosterDate').change(function(){
      var txt =  $("#txtRosterDate").val();
      var month = txt.split('-');
      var monthName ="";
      switch(month[1]){
        case '01' : monthName = "January, " + month[0];break;
        case '02' : monthName = "February, " + month[0];break;
        case '03' : monthName = "March, " + month[0];break;
        case '04' : monthName = "April, " + month[0];break;
        case '05' : monthName = "May, " + month[0];break;
        case '06' : monthName = "June, " + month[0];break;
        case '07' : monthName = "July, " + month[0];break;
        case '08' : monthName = "August, " + month[0];break;
        case '09' : monthName = "September, " + month[0];break;
        case '10' : monthName = "October, " + month[0];break;
        case '11' : monthName = "November, " + month[0];break;
        case '12' : monthName = "December, " + month[0];break;
      }
      $('#rosterMonth').text(monthName);

      $.get('/employee/roster/'+month[1]+'/'+month[0],function(data){
        console.log(data);
        if(data.length<=0){
          //alert("No roster found of month "+monthName+"!");
          console.log('No Roster Found!');

          $('#no_roster_info').show();
          $('#table_roster').hide();
          
          $('#total_passed').text("-").change();
          $('#total_total').text("-").change();
          $('#progress_total').width("0%");

          $('#absent_taken').text("-").change();
          $('#absent_allowed').text("-").change();
          $('#progress_absent').width("0%");

          $('#present_present').text("-").change();
          $('#present_total').text("-").change();
          $('#progress_present').width("0%");

          $('#late_come').text("-").change();
          $('#late_allowed').text("-").change();
          $('#progress_late').width("0%");
        }else{
          $('#no_roster_info').hide();
          $('#table_roster').show();

          $totalRoster = data.length;
          $('#total_passed').text(data.length).change();
          $('#total_total').text(data.length).change();
          $('#progress_total').width("100%").animate({
              opacity: 0.25,
              left: "+=50",
              width: "swing"
            }, 500, function() {
              // Animation complete.
          });

          $absentDays = 0;
          $presentDays = 0;
          $holidaysCount = 0;
          $lateCount =0;
          for($i=0;$i<data.length;$i++){
            //console.log(data[$i]);
            if(data[$i].is_holiday == "H" || data[$i].is_holiday == "O")
              $holidaysCount++;
            else if(data[$i].final_half_1 == 'AB' && data[$i].final_half_2 == 'AB')
              $absentDays++;
            else if(data[$i].final_half_1 == 'PR' && data[$i].final_half_2 == 'PR')
              $presentDays++;
            else if(data[$i].final_half_1 == 'AB' && data[$i].final_half_2 == 'PR')
              $lateCount++;
          }
          $('#absent_taken').text($absentDays).change();
          $('#absent_allowed').text((data.length-$holidaysCount)).change();
          $progress_absent = 0;
          $progress_absent = ($absentDays / (data.length - ($presentDays + $holidaysCount)))*100;
          $('#progress_absent').width($progress_absent.toString()+"%").animate({
              //opacity: 0.25,
              left: "+=50",
              width: "swing"
            }, 500, function() {
              // Animation complete.
          });

          $toBePresentDays = $totalRoster - $holidaysCount;
          $('#present_present').text($presentDays).change();
          $('#present_total').text($toBePresentDays).change();
          $('#progress_present').width(($presentDays*100/$toBePresentDays).toString()+"%");

          $('#late_come').text($lateCount).change();
          $('#late_allowed').text("-").change();
          $('#progress_late').width($lateCount);
        }
    });
    });
  });


   

</script>

@endsection