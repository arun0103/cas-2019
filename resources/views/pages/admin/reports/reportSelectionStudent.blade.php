@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="loading">Loading&#8230;</div>
    <h3>Report Parameters</h3>
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="get" action="/generateReportStudent">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-4">
                        <label for="select_reportType">Report Type</label>
                        <select id="select_reportType" class="form-control select2" data-placeholder="Select Type of Report" name="selectedReportType" required onchange="reportTypeChanged()">
                            <option></option>
                            <option value="rep_total_absent_by_grade">Total Absent [Grade]</option> 
                            <option value="rep_total_present_by_grade">Total Present [Grade]</option> 
                            <option value="rep_absent">Absent</option>
                            <!-- <option value="rep_annual_summary">Annual Summary</option> -->
                            <option value="rep_attendance">Attendance</option>
                            <option value="rep_early_in">Early In</option>
                            <option value="rep_early_out">Early Out</option>
                            <!-- <option value="rep_student_list">Student List</option> -->
                            <option value="rep_late_in">Late In</option>
                            <!-- <option value="rep_leave_registered">Leave Registered</option> -->
                            <!-- <option value="rep_mismatch">Mismatch</option> -->
                        </select>
                    </div>
                
                    <div class="col-sm-4" id="div_grade">
                        <label for="select_grade">Grade</label>
                        <select id="select_grade" class="form-control select2" multiple data-placeholder="Select Grade" name="selectedGrades[]" required onchange="gradeSelected()">
                            <option></option>
                            @foreach($grades as $grade)
                                <option value="{{$grade->grade_id}}">{{$grade->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4" id="div_section">
                        <label for="select_section">Section</label>
                        <select id="select_section" class="form-control select2" multiple data-placeholder="Select Section" name="selectedSections[]" required onchange="sectionSelected()">
                            <option></option>
                            @foreach($sections as $section)
                                <option value="{{$section->section_id}}">{{$section->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4" id="div_student">
                        <label for="select_student">Student</label>
                        <select id="select_student" class="form-control select2" multiple data-placeholder="Select Student" name="selectedStudents[]">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-sm-4" id="div_from">
                        <label for="datepicker_from">From</label>
                        <div class="input-group date">
                            <div class="input-group-addon left-addon">
                                <input type="text" class="form-control pull-right  percent100" id="datepicker_from" autocomplete="off" placeholder="From Date" name="fromDate">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" id="div_to">
                        <label for="datepicker_to">To</label>
                        <div class="input-group date">
                            <div class="input-group-addon left-addon">
                                <input type="text" class="form-control pull-right  percent100"  style="width:100%" id="datepicker_to" autocomplete="off" placeholder="To Date" name="toDate">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <input type="hidden" id="generate_type" name="generate_type">
                    <div class="col-sm-2">
                        <button class="btn btn-primary" onclick="$('#generate_type').val('pdf').change()">Generate PDF</button>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" onclick="$('#generate_type').val('excel').change()">Download Excel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('footer')
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function () {
        console.log(screen.width + " x " + screen.height);
        $('#employeeTable').DataTable({
            'paging'        : true,
            'lengthChange'  : true,
            'searching'     : true,
            'ordering'      : true,
            'info'          : true,
            'autoWidth'     : true,
            'scrollX'       : true
        });
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            weekStart: 0,
            autoclose: true,
            todayHighlight: true,
            orientation: "auto"
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        $('.loading').hide();

    });
    function gradeSelected(){
        var selectedGradeID = $('#select_grade').val();
        $('#select_section').empty();
        for(var i = 0; i< selectedGradeID.length; i++){
            $.get('/getSectionOfGrade/'+selectedGradeID[i], function($result){
                for(var j=0 ; j < $result.length ; j++){
                    var sections = $('<option value="'+$result[j]['section_id']+'">'+$result[j]['name']+'</option>');
                    $('#select_section').append(sections).change();
                }
            });
        }
        
        //$('#select_employee').val([]).change();
    }
    function sectionSelected(){
        $('#select_student').val([]).change();
        if($('#select_grade').val().length>0 && $('#select_section').val().length>0){
            populateStudents();
        }else{
            $('#select_student').val([]).change();
        }
    }
    function reportTypeChanged(){
        console.log("Selected Report: " + $('#select_reportType').val());
        
        switch($('#select_reportType').val()){
            case 'rep_total_absent_by_grade' :
                $('#select_section').attr('required',false);
                $('#select_student').attr('required',false);
               
                $('#div_grade').hide("slow");
                $('#div_section').hide("slow");
                $('#div_student').hide("slow");
                $('#div_from').hide("slow");
                $('#div_to').hide("slow");

                $('#div_grade').show("fast");
                $('#div_from').show("fast");
                $('#div_to').show("fast");
                break;
            case 'rep_total_present_by_grade' :
                $('#select_section').attr('required',false);
                $('#select_student').attr('required',false);
               
                $('#div_grade').hide("slow");
                $('#div_section').hide("slow");
                $('#div_student').hide("slow");
                $('#div_from').hide("slow");
                $('#div_to').hide("slow");

                $('#div_grade').show("fast");
                $('#div_from').show("fast");
                $('#div_to').show("fast");
                break;
            case 'rep_absent': 
                $('#select_section').attr('required',true);
                $('#select_student').attr('required',true);

                $('#div_grade').hide("slow");
                $('#div_section').hide("slow");
                $('#div_student').hide("slow");
                $('#div_from').hide("slow");
                $('#div_to').hide("slow");


                $('#div_grade').show("fast");
                $('#div_section').show("fast");
                $('#div_student').show("fast");
                $('#div_from').show("fast");
                $('#div_to').show("fast");
                break; // completed
            // case 'rep_annual_summary': break; // incomplete
            // case 'rep_attendance': break; // completed
            // case 'rep_early_in': break; // completed
            // case 'rep_early_out': break; //completed
            // case 'rep_late_in': break;
            // case 'rep_student_list': break;
            // case 'rep_leave_registered': break;
            // case 'rep_mismatch': break;

            default:
                $('#select_section').attr('required',false);
                $('#select_student').attr('required',false);

                $('#select_grade').empty();
                $('#select_section').empty();
                $('#select_student').empty();
                $('#datepicker_from').empty();
                $('#datepicker_o').empty();

                $('#div_grade').hide("slow");
                $('#div_section').hide("slow");
                $('#div_student').hide("slow");
                $('#div_from').hide("slow");
                $('#div_to').hide("slow");

                $('#div_grade').show("fast", 'swing');
                $('#div_section').show("slow", 'swing');
                $('#div_student').show("fast", 'swing');
                $('#div_from').show("slow", 'swing');
                $('#div_to').show("slow", 'swing');


            
        }
    }
    function populateStudents(){               
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var grades_selected_count = $('#select_grade').select2('val').length;
        //console.log(grades_selected_count);
        var original_grades = $('#select_grade').val();
        var grade_array = "";
        for(var i =0; i<grades_selected_count; i++){
            grade_array += original_grades[i];
            if(i < grades_selected_count -1)
                grade_array+= ",";
        }
        var sections_selected_count = $('#select_section').val().length;
        //console.log(sections_selected_count);
        var original_sections = $('#select_section').val();
        var section_array = "";
        for(var i =0; i<sections_selected_count; i++){
            section_array += original_sections[i];
            if(i < sections_selected_count -1)
                section_array+= ",";
        }
        var formData = {
            grades: grade_array,
            sections: section_array
        };
        $.ajax({
            type: 'GET',
            url: '/getStudents',
            data: formData,
            dataType: 'json',
            success: function (data) {
                //console.log("Employees" + JSON.stringify(data));
                var index = 0;
                $('#select_student').empty();
                $('#select_student').append('<option></option>').change();
                console.log(data.length);
                console.log(data);
                $.each(data, function(index,value){
                    for(var i =0; i<value.length; i++){
                        var newStudent = $('<option value="'+value[i].student_id+'">'+value[i].name+'</option>');
                        $('#select_student').append(newStudent).change();
                    }
                    //index++;
                });
            }
        });
    }

</script>

@endsection