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
            <form class="form-horizontal" method="get" action="/generateReport">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-3">
                        <label for="select_branch">Branch</label>
                        <select id="select_branch" class="form-control select2" multiple data-placeholder="Select Branch" name="selectedBranches[]" required onchange="branchSelected()">
                            <option></option>
                            @foreach($branches as $branch)
                                <option value="{{$branch->branch_id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="select_department">Department</label>
                        <select id="select_department" class="form-control select2" multiple data-placeholder="Select Department" name="selectedDepartments[]" required onchange="departmentSelected()">
                            <option></option>
                            @foreach($departments as $department)
                                <option value="{{$department->department_id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="select_category">Category</label>
                        <select id="select_category" class="form-control select2" multiple data-placeholder="Select Category" name="selectedCategories[]" required onchange="categorySelected()">
                            <option></option>
                            @foreach($categories as $category)
                                <option value="{{$category->category_id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="select_employee">Employee</label>
                        <select id="select_employee" class="form-control select2" multiple data-placeholder="Select Employee" name="selectedEmployees[]">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                            <label for="select_shift">Shift</label>
                            <select id="select_shift" class="form-control select2" multiple data-placeholder="Select Shift" name="selectedShifts[]">
                                <option></option>
                                @foreach($shifts as $shift)
                                    <option value="{{$shift->shift_id}}">{{$shift->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="col-sm-3">
                        <label for="select_reportType">Report Type</label>
                        <select id="select_reportType" class="form-control select2" data-placeholder="Select Type of Report" name="selectedReportType" required>
                            <option></option>
                            <option value="rep_absent">Absent</option>
                            <option value="rep_annual_summary">Annual Summary</option>
                            <option value="rep_attendance">Attendance</option>
                            <!-- <option value="rep_canteen_1">Canteen 1</option>
                            <option value="rep_canteen_2">Canteen 2</option> -->
                            <option value="rep_daily_punch">Daily Punch</option>
                            <option value="rep_early_in">Early In</option>
                            <option value="rep_early_out">Early Out</option>
                            <option value="rep_employee_list">Employee List</option>
                            <!-- <option value="rep_form_12">Form 12</option>
                            <option value="rep_form_14_el">Form 14 - EL</option>
                            <option value="rep_form_b_cl">Form B-CL</option> -->
                            <option value="rep_late_in">Late In</option>
                            <option value="rep_leave_registered">Leave Registered</option>
                            <option value="rep_manpower">Manpower</option>
                            <option value="rep_mismatch">Mismatch</option>
                            <option value="rep_movement">Movement</option>
                            <option value="rep_muster">Muster</option>
                            <option value="rep_overstay">Overstay</option>
                            <option value="rep_punch_card">Punch Card</option>
                            <option value="re_register">Register</option> 
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="datepicker_from">From</label>
                        <div class="input-group date">
                            <div class="input-group-addon left-addon">
                                <input type="text" class="form-control pull-right  percent100" id="datepicker_from" autocomplete="off" placeholder="From Date" name="fromDate">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
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
    function branchSelected(){
        $('#select_department').val([]).change();
        $('#select_employee').val([]).change();
    }
    function departmentSelected(){
        $('#select_employee').val([]).change();
        $('#select_category').val([]).change();  
    }
    function categorySelected(){
        $('#select_employee').val([]).change();
        if($('#select_category').val().length>0){
            populateEmployees();
        }else{
            $('#select_employee').val([]).change();
        }
    }
    function populateEmployees(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var branches_selected_count = $('#select_branch').select2('val').length;
        var original_branches = $('#select_branch').val();
        var branch_array = "";
        for(var i =0; i<branches_selected_count; i++){
            branch_array += original_branches[i];
            if(i < branches_selected_count -1)
                branch_array+= ",";
        }
        var departments_selected_count = $('#select_department').val().length;
        var original_departments = $('#select_department').val();
        var department_array = "";
        for(var i =0; i<departments_selected_count; i++){
            department_array += original_departments[i];
            if(i < departments_selected_count -1)
                department_array+= ",";
        }
        var categories_selected_count = $('#select_category').val().length;
        var original_categories = $('#select_category').val();
        var category_array = "";
        for(var i =0; i<categories_selected_count; i++){
            category_array += original_categories[i];
            if(i < categories_selected_count -1)
                category_array+= ",";
        }
        var formData = {
            branches: branch_array,
            departments: department_array,
            categories: category_array
        };
        $.ajax({
            type: 'GET',
            url: '/getEmployees',
            data: formData,
            dataType: 'json',
            success: function (data) {
                //console.log("Employees" + JSON.stringify(data));
                var index = 0;
                $('#select_employee').empty();
                $('#select_employee').append('<option></option>').change();
                console.log(data.length);
                console.log(data);
                $.each(data, function(index,value){
                    for(var i =0; i<value.length; i++){
                        var newEmployee = $('<option value="'+value[i].employee_id+'">'+value[i].name+'</option>');
                        $('#select_employee').append(newEmployee).change();
                    }
                    //index++;
                });
            }
        });
    }

</script>

@endsection