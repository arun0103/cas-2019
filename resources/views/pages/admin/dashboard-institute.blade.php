@extends('layouts.master')
@section('head')
  <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
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
              <li class="breadcrumb-item active">Dashboard | Admin {{date('Y-m-d')}}</li>
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
            <div class="info-box" id="div-total-employees">
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
            <div class="info-box mb-3" id="div-absent-employees">
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
            <div class="info-box mb-3" id="div-present-employees">
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
            <div class="info-box mb-3" id="div-late-employees">
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
            <div class="info-box" id="div-total-students">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total</span>
                <span class="info-box-number" id="student-total">{{$studentDetails['total']}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" id="div-absent-students">
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
            <div class="info-box mb-3" id="div-present-students">
              <span class="info-box-icon bg-success elevation-1"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Present</span>
                <span class="info-box-number" id="student-present">{{$studentDetails['present']}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3" id="div-late-students">
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
    
    <div id="div-total-employees-container" class="no-display">
      @include('pages.dashboard.employees.total-employees-table')
    </div>
    <div id="div-absent-employees-container" class="no-display">
      @include('pages.dashboard.employees.absent-employees-table')
    </div>
    <div id="div-present-employees-container" class="no-display">
      @include('pages.dashboard.employees.present-employees-table')
    </div>
    <div id="div-late-employees-container" class="no-display">
      @include('pages.dashboard.employees.late-employees-table')
    </div>

    <div id="div-total-students-container" class="no-display">
      @include('pages.dashboard.total-students-table')
    </div>
    <div id="div-absent-students-container" class="no-display">
      @include('pages.dashboard.absent-students-table')
    </div>
    <div id="div-present-students-container" class="no-display">
      @include('pages.dashboard.present-students-table')
    </div>
    <div id="div-late-students-container" class="no-display">
      @include('pages.dashboard.late-students-table')
    </div>
  
@endsection

@section('footer')
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script>
  $(document).ready(function(){
    $('.loading').hide();
    
    // SET AUTOMATIC PAGE RELOAD TIME TO 1000 MILISECONDS (1 SECOND * seconds we want).
    setInterval('refreshPageContents()', 1000*60);
  });
  function refreshPageContents() { 
    $.get('/refreshDashboard/Institute',function(data){
      
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

  $('#div-total-employees').click(function (e){
    // $('#total-employees-table').DataTable( {
    //     "ajax": {
    //         "url": "/getTotalEmployees/",
    //         "dataSrc": ""
    //     },
    //     "columns": [
    //       { "data": "name" },
    //         { "data": "branch.name" },
    //         { "data": "department.name" },
    //         { "data": "designation.name" },
    //         { "data": "punch_records.punch_1","defaultContent":"<i style='color:red'>No Data</i>" }
    //     ]
    // } );
    $.ajax({
      'url': "/getTotalEmployees/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
      console.log(data);
      if ( $.fn.dataTable.isDataTable( '#total-employees-table' ) ) {
          table = $('#total-employees-table').DataTable();
        }else{
          $('#total-employees-table').dataTable({
            "aaData": data,
            "columns": [
                { "data": "name" },
                { "data": "branch.name" },
                { "data": "department.name" },
                { "data": "designation.name" },
                { "data": "punch_records.punch_1","defaultContent":"<i style='color:red'>No Data</i>" }
            ]
          });
        }
    });
    $("#div-total-employees-container").addClass('display-block').removeClass('no-display');

    $('#div-present-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-present-students-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');
    

    $('html, body').animate({
        scrollTop: $("#div-total-employees-container").offset().top
    }, 1000);
  });
  $('#div-absent-employees').click(function (e){
    $.ajax({
      'url': "/getAbsentEmployees/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
      console.log(data);
      if ( $.fn.dataTable.isDataTable( '#absent-employees-table' ) ) {
          table = $('#absent-employees-table').DataTable();
        }else{
          $('#absent-employees-table').dataTable({
            "aaData": data,
            "columns": [
                { "data": "name" },
                { "data": "branch.name" },
                { "data": "department.name" },
                { "data": "designation.name" },
                { "data": "applied_leaves[0].leave_from","defaultContent":"<i style='color:red'>Not Applied</i>"},
                { "data": "applied_leaves[0].leave_to","defaultContent":"<i style='color:red'>Not Applied</i>"}            
            ]
          });
        }
    });
    $("#div-absent-employees-container").addClass('display-block').removeClass('no-display');

    $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-present-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-present-students-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');
    
    $('html, body').animate({
        scrollTop: $("#div-absent-employees-container").offset().top
    }, 1000);
  });
  $('#div-present-employees').click(function (e){
    $.ajax({
      'url': "/getPresentEmployees/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
      console.log(data);
      if ( $.fn.dataTable.isDataTable( '#present-employees-table' ) ) {
          table = $('#present-employees-table').DataTable();
        }else{
          table = $('#present-employees-table').dataTable({
            "aaData": data,
            "columns": [
                { "data": "employee.name" },
                { "data": "employee.branch.name" },
                { "data": "employee.department.name" },
                { "data": "employee.designation.name" },
                { "data": "punch_1" }
            ]
          });
        }
      
    });
    $("#div-present-employees-container").addClass('display-block').removeClass('no-display');

    $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-present-students-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');
    
    $('html, body').animate({
        scrollTop: $("#div-present-employees-container").offset().top
    }, 1000);
  });
  $('#div-late-employees').click(function (e){
    $.ajax({
      'url': "/getLateEmployees/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
      console.log("late employees: "+ data);
      if ( $.fn.dataTable.isDataTable( '#late-employees-table' ) ) {
          table = $('#late-employees-table').DataTable();
        }
        else {
          table = $('#late-employees-table').dataTable({
            "aaData": data,
            "columns": [
                { "data": "employee.name" },
                { "data": "employee.branch.name" },
                { "data": "employee.department.name" },
                { "data": "employee.designation.name" },
                { "data": "late_in" }
            ]
          });
        }
    });
    $("#div-late-employees-container").addClass('display-block').removeClass('no-display');

    $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-present-employees-container').addClass('no-display').removeClass('display-block');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-present-students-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');
    
    $('html, body').animate({
        scrollTop: $("#div-late-employees-container").offset().top
    }, 1000);
  });

  $('#div-total-students').on('click',function(){
    // if ( $.fn.dataTable.isDataTable( '#total-students-table' ) ) {
    //   table = $('#total-students-table').Datatable();
      
    // }else{
    //   table = $('#total-students-table').DataTable( {
    //     "ajax": {
    //         "url": "/getTotalStudents/",
    //         "dataSrc": ""
    //     },
    //     "columns": [
    //       { "data": "name" },
    //       { "data": "grade.name" },
    //       { "data": "section.name" },
    //       { "data": "guardian_name" },
    //       { "data": "contact_1_number" },
    //       { "data": "contact_2_number" },
    //     ]
    // } );
    // }
    
    $.ajax({
      'url': "/getTotalStudents/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
        if ( $.fn.dataTable.isDataTable( '#total-students-table' ) ) {
          table = $('#total-students-table').DataTable();
        }
        else {
            table = $('#total-students-table').dataTable({
                      "aaData": data,
                      "columns": [
                          { "data": "name" },
                          { "data": "grade.name" },
                          { "data": "section.name" },
                          { "data": "guardian_name" },
                          { "data": "contact_1_number" },
                          { "data": "contact_2_number" },
                      ]
                    });
        }
      
      });
      $('#div-total-students-container').addClass('display-block').removeClass('no-display');

      $('#div-present-students-container').addClass('no-display').removeClass('display-block');
      $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
      $('#div-late-students-container').addClass('no-display').removeClass('display-block');

      $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
      $('#div-present-employees-container').addClass('no-display').removeClass('display-block');
      $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
      $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

      $('html, body').animate({
          scrollTop: $("#div-total-students-container").offset().top
      }, 1000);    
  });
  $('#div-present-students').on('click',function(){
    $.ajax({
      'url': "/getPresentStudents/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
      console.log(data);
      if ( $.fn.dataTable.isDataTable( '#present-students-table' ) ) {
          table = $('#present-students-table').DataTable();
      }
      else {
          table = $('#present-students-table').DataTable({
                    "aaData": data,
                    "columns": [
                        { "data": "student.name" },
                        { "data": "student.grade.name" },
                        { "data": "student.section.name" },
                        { "data": "student.guardian_name" },
                        { "data": "student.contact_1_number" },
                        { "data": "student.contact_2_number" },
                        { "data": "punch_1" },
                    ]
                  });
        }
    });
    $('#div-present-students-container').addClass('display-block').removeClass('no-display');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');

    $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-present-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

    $('html, body').animate({
        scrollTop: $("#div-present-students-container").offset().top
    }, 1000);    
  });

  $('#div-absent-students').on('click',function(){
    $.ajax({
      'url': "/getAbsentStudents/",
      'method': "GET",
      'contentType': 'application/json'
      }).done( function(data) {
        if ( $.fn.dataTable.isDataTable( '#absent-students-table' ) ) {
            table = $('#absent-students-table').DataTable();
        }
        else {
          table = $('#absent-students-table').dataTable({
                    "aaData": data,
                    "columns": [
                        { "data": "name" },
                        { "data": "grade.name" },
                        { "data": "section.name" },
                        { "data": "guardian_name" },
                        { "data": "contact_1_number" },
                        { "data": "contact_2_number" },
                    ]
                  });
        }
      
    });
    $('#div-absent-students-container').addClass('display-block').removeClass('no-display');

    $('#div-total-students-container').addClass('no-display').removeClass('display-block');
    $('#div-present-students-container').addClass('no-display').removeClass('display-block');
    $('#div-late-students-container').addClass('no-display').removeClass('display-block');

    $('#div-total-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-present-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-absent-employees-container').addClass('no-display').removeClass('display-block');
    $('#div-late-employees-container').addClass('no-display').removeClass('display-block');

    $('html, body').animate({
        scrollTop: $("#div-absent-students-container").offset().top
    }, 1000);
  });

</script>
@endsection