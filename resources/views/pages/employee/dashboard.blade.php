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
</style>
<link rel="stylesheet" href="{{asset('css/MonthPicker.css')}}">
<link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
@endsection
@section('content')
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
              <li class="breadcrumb-item active">Dashboard | Employee</li>
              <input type="hidden" id="input_employee_id" value="{{Session::get('user_id')}}">
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
            <div class="info-box">
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
            <div class="info-box mb-3">
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
            <div class="info-box mb-3">
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
            <div class="info-box mb-3">
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
                      <a href="#" class="dropdown-item">Action</a>
                      <a href="#" class="dropdown-item">Another action</a>
                      <a href="#" class="dropdown-item">Something else here</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">Separated link</a>
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
                    <table id="table_roster" class="table table-bordered table-striped">
                      <thead>
                        <th style="max-width:100px">Date</th>
                        <th>Shift</th>
                        <th>Half 1</th>
                        <th>Half 2</th>
                        <th>Actions</th>
                      </thead>
                      <tbody id="tbody_roster">
                      </tbody>
                    </table>
                    </div>
                    <p id="no_roster_info">You have no rosters assigned for this month. Please Contact Admin!</p>


                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <!-- <canvas id="salesChart" height="180" style="height: 180px;"></canvas> -->
                    </div>
                    <!-- /.chart-responsive -->
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
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fa fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">$35,210.43</h5>
                      <span class="description-text">TOTAL REVENUE</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"><i class="fa fa-caret-left"></i> 0%</span>
                      <h5 class="description-header">$10,390.90</h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fa fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fa fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title" id= "report_monthly">Monthly Recap Report |</h5>


                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <a href="#" class="dropdown-item">Action</a>
                      <a href="#" class="dropdown-item">Another action</a>
                      <a href="#" class="dropdown-item">Something else here</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">Separated link</a>
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
                    <p class="text-center">
                      <strong>Employee Present: 1 Jan, 2014 - 30 Jul, 2014</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                      Add Products to Cart
                      <span class="float-right"><b>160</b>/200</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <div class="progress-group">
                      Complete Purchase
                      <span class="float-right"><b>310</b>/400</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" style="width: 75%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">Visit Premium Page</span>
                      <span class="float-right"><b>480</b>/800</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      Send Inquiries
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fa fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">$35,210.43</h5>
                      <span class="description-text">TOTAL REVENUE</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-warning"><i class="fa fa-caret-left"></i> 0%</span>
                      <h5 class="description-header">$10,390.90</h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fa fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fa fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
              </div>
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
<script>
  $(document).ready(function(){
    
    $('#no_roster_info').hide();
    $('#table_roster').hide(); 
      
    var date = new Date();
    var month = date.getMonth();
    var year = date.getYear();
    var monthName = ""; 
    switch(month){
        case 1 : monthName = "January, " + year;break;
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
      $('#txtRosterDate').text(monthName); 
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
          $lateCount =0;
          $('#tbody_roster').empty();
          for($i=0;$i<data.length;$i++){
            //console.log(data[$i]);
            var rosterDetail = '<tr><td>'+data[$i].date+'</td><td>'+data[$i].shift['name']+'</td><td>'+data[$i]['final_half_1']+'</td><td>'+data[$i]['final_half_2']+'</td><td><button>Apply Leave</button></td></tr>';
            $('#tbody_roster').append(rosterDetail);
           

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