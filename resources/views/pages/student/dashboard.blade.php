@extends('layouts.master')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .ui-datepicker-calendar {
    display: none ;
  }
  .container_table{
    max-height: 185px !important;
    overflow-y: scroll;
  }
  #no_roster_info{
    text-align: center;
  }
</style>
<link rel="stylesheet" href="{{asset('css/MonthPicker.css')}}">
<link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
@endsection
@section('content')
  <input type='hidden' id='student_id' value="{{Session::get('user_id')}}">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard | Student</li>
              <input type="hidden" id="input_student_id" value="{{Session::get('user_id')}}">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box"  id="total_days_roster">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Days</span>
                <span class="info-box-number">{{ $roster['total'] }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" id="absent_days_roster">
              <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Absent Days</span>
                <span class="info-box-number">{{$roster['absent']}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3"  id="present_days_roster">
              <span class="info-box-icon bg-success elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Present Days</span>
                <span class="info-box-number">{{$roster['present']}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" id="late_days_roster">
              <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Late Days
                </span>
                <span class="info-box-number">{{$roster['late']}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div id="div-total-student-container" class="no-display">
          @include('pages.dashboard.students.total-students-roster')
        </div>
        <div id="div-absent-student-container" class="no-display">
          @include('pages.dashboard.students.absent-students-roster')
        </div>
        <div id="div-present-student-container" class="no-display">
          @include('pages.dashboard.students.present-students-roster')
        </div>
        <div id="div-late-student-container" class="no-display">
          @include('pages.dashboard.students.late-students-roster')
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="col-sm-3">
                  <div class="form-group">
                    <h5 class="card-title" id= "report_roster">Roster Report </h5>
                  </div>
              </div>

                <div class="card-tools"> 
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <span>Under Construction!</span>
                      <!-- <a href="#" class="dropdown-item">Action</a>
                      <a href="#" class="dropdown-item">Another action</a>
                      <a href="#" class="dropdown-item">Something else here</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">Separated link</a> -->
                    </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-left">
                      <strong>Roster Schedule of : <input type='month' id='txtRosterDate' value="2019-01" required /></strong>
                    </p>
                  <div class="container_table">
                    <table id="table_roster" class="table table-bordered table-striped" style="display:hidden">
                      <thead>
                        <th style="max-width:100px">Date</th>
                        <th>Roster</th>
                        <th>Punch In</th>
                        <th>Punch Out</th>
                        <th>Actions</th>
                      </thead>
                      <tbody id="tbody_roster">
                      </tbody>
                    </table>
                    </div>
                    <p id="no_roster_info">Dear User,
                    You have no rosters assigned for this month. 
                    <span style="text-align:center"><button type="button" class="btn btn-tool" data-widget="generate" style="margin:auto;display:block;">Generate</button></span>
                    </p>
                    
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Performance</strong>
                    </p>

                    <div class="progress-group">
                      Total
                      <span class="float-right"><b id="total_passed"></b> / <b id="total_total"></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" id="progress_total"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      Absent
                      <span class="float-right"><b id="absent_taken"></b> / <b id="absent_allowed"></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" id="progress_absent"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Present</span>
                      <span class="float-right"><b id="present_present"></b> / <b id="present_total"></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" id="progress_present"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Late
                      <span class="float-right"><b id="late_come"> </b> / <b id="late_allowed"></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" id="progress_late"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
             
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        
        <!-- /.row -->

        <!-- Main row -->
        
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('footer')
<script src="{{asset('js/plugins/jquery/MonthPicker.js')}}"></script>
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script type='text/javascript'>
  
  var student_id = $('#student_id').val();
  $(document).ready(function(){
   
    $('#no_roster_info').hide();
    $('#table_roster').hide(); 
      
    var date = new Date();
    var month = date.getMonth() + 1 ; // January is 0
    var year = date.getFullYear();
    //console.log(year + '-'+ month);
    var monthName = ""; 
    switch(month){
        case 1 : monthName = "January, " + year; break;
        case 2 : monthName = "February, " + year;break;
        case 3 : monthName = "March, " + year;break;
        case 4 : monthName = "April, " + year;break;
        case 5 : monthName = "May, " + year;break;
        case 6 : monthName = "June, " + year;break;
        case 7 : monthName = "July, " + year;break;
        case 8 : monthName = "August, " + year;break;
        case 9 : monthName = "September, " + year;break;
        case 10 : monthName = "October, " + year;break;
        case 11 : monthName = "November, " + year;break;
        case 12 : monthName = "December, " + year;break;
      } 
      $('#txtRosterDate').val(year+'-'+month).change();  
      $('#txtRosterDate').text(monthName).change(); 
      updateRosterReport();
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
      updateRosterReport();
    });


    
    
  });

  $('#total_days_roster').click(function(){
    //console.log('clicked by :'+student_id);
    var url = "/dashboard/getTotalRosterSummary/";
    if(student_id.includes("/")==true){
      var parts = student_id.split("/");
      var index = 0;
      for(var i =0; i<parts.length;i++){
        url += parts[i];
        if(i != parts.length){
          url += "%2F";
        }

      }
    }else{
      url = "/dashboard/getTotalRosterSummary/"+student_id;
    }
    $.ajax({
      'url': url,
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
        //console.log(data);
        $('#span_total_roster_days').text(data.totalRosters).change();
        $('#span_total_classes').text(data.totalClasses).change();
        $('#span_total_holidays').text(data.totalHolidays).change();
        $('#span_total_offs').text(data.totalOffs).change();
        $('#span_total_leaves').text('0');
      });
      $('#div-total-student-container').addClass('display-block').removeClass('no-display');

      $('#div-present-student-container').addClass('no-display').removeClass('display-block');
      $('#div-absent-student-container').addClass('no-display').removeClass('display-block');
      $('#div-late-student-container').addClass('no-display').removeClass('display-block');


      $('html, body').animate({
          scrollTop: $("#report_roster").offset().top
      }, 1000);    
  });

  $('#present_days_roster').click(function(){
    var url = "/dashboard/getTotalPresentSummary/";
    if(student_id.includes("/")==true){
      var parts = student_id.split("/");
      var index = 0;
      for(var i =0; i<parts.length;i++){
        url += parts[i];
        if(i != parts.length){
          url += "%2F";
        }

      }
    }else{
      url = "/dashboard/getTotalPresentSummary/"+student_id;
    }
    
    $.ajax({
      'url': url,
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
        console.log(data);
       
      });
      $('#div-present-student-container').addClass('display-block').removeClass('no-display');
      
      $('#div-total-student-container').addClass('no-display').removeClass('display-block');
      $('#div-absent-student-container').addClass('no-display').removeClass('display-block');
      $('#div-late-student-container').addClass('no-display').removeClass('display-block');


      $('html, body').animate({
          scrollTop: $("#report_roster").offset().top
      }, 1000);    
  });
  $('#absent_days_roster').click(function(){
    $.ajax({
      'url': "/dashboard/getTotalAbsentSummary/"+student_id,
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
        console.log(data);
        if ( $.fn.dataTable.isDataTable( '#absent-summary-table' ) ) {
          table = $('#absent-summary-table').DataTable();
      }
      else {
          table = $('#absent-summary-table').DataTable({
                    "aaData": data,
                    "columns": [
                        { "data": "date", "defaultContent":"N/A"  },
                        { "data": "0", "defaultContent":"N/A"  },
                    ]
                  });
        }
       
      });
      $('#div-absent-student-container').addClass('display-block').removeClass('no-display');
      
      $('#div-total-student-container').addClass('no-display').removeClass('display-block');
      $('#div-present-student-container').addClass('no-display').removeClass('display-block');
      $('#div-late-student-container').addClass('no-display').removeClass('display-block');


      $('html, body').animate({
          scrollTop: $("#report_roster").offset().top
      }, 1000);    
  });
  $('#late_days_roster').click(function(){
    
  });
  function updateRosterReport(){
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

      $.get('/student/roster/'+month[1]+'/'+month[0],function(data){
        //console.log(data);
        if(data.length<=0){ // When roster is not found. 
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
        }
        else{
          $('#no_roster_info').hide();
          $('#table_roster').show();
                   
          var today = new Date();
          $totalRoster = data.length;
          
          $('#total_passed').text(data.length).change();
          var month_check = 0;
          switch(month[1][0]){
            case '0': month_check = month[1][1];break;
            case '1': month_check = month[1];break;
          }
          if(month_check == (today.getMonth()+1).toString() && month[0] == today.getFullYear()){
            $('#total_passed').text(today.getDate()).change();
            $('#progress_total').width((today.getDate()*100/$totalRoster).toString()+"%").animate({
                opacity: 0.25,
                left: "+=50",
                width: "swing"
              }, 3000, function() {
                // Animation complete.
            });
          }else{
            $('#progress_total').width("100%").animate({
                opacity: 0.25,
                left: "+=50",
                width: "swing"
              }, 3000, function() {
                // Animation complete.
            });
          }
          $('#total_total').text(data.length).change();
          
          $absentDays = 0;
          $presentDays = 0;
          $holidaysCount = 0;
          $lateCount = 0;
          $('#tbody_roster').empty();
          var punch_in, punch_out;
          var punch_in_time = null;
          var punch_out_time = null;
          var roster_type;
          for($i=0;$i<data.length;$i++){
            //console.log(data[$i]);
            if(data[$i]['punch_in'] != null){
              punch_in = data[$i]['punch_in'].split(' ');
              punch_in_time = punch_in[1];
            }else{
              punch_in_time = "N/A";
            }
            if(data[$i]['punch_out'] != null){
              punch_out = data[$i]['punch_out'].split(' ');
              punch_out_time = punch_out[1];
            }else{
              punch_out_time = "N/A";
            }
            switch(data[$i]['is_holiday']){
              case 'C': roster_type = '<span style="color:green">Class</span>';break;
              case 'H': roster_type = '<span style="color:red">Holiday</span>';break;
              case 'L': roster_type = '<span style="color:blue">Leave Taken</span>';break;
            }
            var rosterDetail ='';
            if(month[1]== today.getMonth()+1){
              if(today.getDate() < $i+1)
                rosterDetail = '<tr><td>'+data[$i].date+'</td><td>'+roster_type+'</td><td>'+punch_in_time+'</td><td>'+punch_out_time+'</td><td><button>Apply Leave</button></td></tr>';
              else
                rosterDetail = '<tr><td>'+data[$i].date+'</td><td>'+roster_type+'</td><td>'+punch_in_time+'</td><td>'+punch_out_time+'</td><td></td></tr>';
            }else
              rosterDetail = '<tr><td>'+data[$i].date+'</td><td>'+roster_type+'</td><td>'+punch_in_time+'</td><td>'+punch_out_time+'</td><td></td></tr>';
            $('#tbody_roster').append(rosterDetail);
           

            if(data[$i].is_holiday == "H" || data[$i].is_holiday == "O")
              $holidaysCount++;
            else if(data[$i].punch_in == null && data[$i].punch_out == null)
              $absentDays++;
            else if(data[$i].punch_in != null)
              $presentDays++;
            else if(data[$i].punch_in != null )
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
            }, 3000, function() {
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
    }

   

</script>

@endsection