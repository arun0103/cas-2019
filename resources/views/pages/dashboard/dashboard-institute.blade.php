@extends('layouts.master-institute')
@section('content')
<div class="loading">Loading&#8230;</div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard | Admin</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <h4 style="margin:0 auto; text-align:center">Employees<h4>
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number" id="employee-total">{{$employeeDetails['total']}}</span>
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
                <span class="info-box-text">Absent</span>
                <span class="info-box-number" id="employee-absent">{{$employeeDetails['total']-$employeeDetails['present']}}</span>
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
                <span class="info-box-text">Present</span>
                <span class="info-box-number" id="employee-present">{{$employeeDetails['present']}}</span>
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
                <span class="info-box-text">Late</span>
                <span class="info-box-number" id="employee-late">{{$employeeDetails['late']}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <hr>
        <h4 style="margin:0 auto; text-align:center">Students<h4>
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number" id="student-total">{{$studentDetails['total']}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Absent</span>
                <span class="info-box-number" id="student-absent">{{$studentDetails['total']-$studentDetails['present']}}</span>
              </div>
            </div>

          </div>

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Present</span>
                <span class="info-box-number" id="student-present">{{$studentDetails['present']}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Late</span>
                <span class="info-box-number" id="student-late">{{$studentDetails['late']}}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@section('footer')
<script>
  $(document).ready(function(){
    $('.loading').hide();
    // SET AUTOMATIC PAGE RELOAD TIME TO 1000 MILISECONDS (1 SECOND * seconds we want).
    setInterval('refreshPageContents()', 1000*60);
  });
  function refreshPageContents() { 
    //location.reload(); 
    //console.log('Reloading page contents');
    $.get('/refreshDashboard/Institute',function(data){
      //console.log(data);
      $('#employee-total').text(data.employee['total']).change();
      $('#employee-present').text(data.employee['present']).change();
      $('#employee-absent').text(data.employee['total'] - data.employee['present']).change();
      $('#employee-late').text(data.employee['late']).change();

      $('#student-total').text(data.student['total']).change();
      $('#student-present').text(data.student['present']).change();
      $('#student-absent').text(data.student['total'] - data.student['present']).change();
      $('#student-late').text(data.student['late']).change();


    });
  }

</script>
@endsection