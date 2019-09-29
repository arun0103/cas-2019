@extends('layouts.master')
@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-container .select-all {
            position: absolute;
            top: 6px;
            right: 4px;
            width: 20px;
            height: 20px;
            margin: auto;
            display: block;
            background: url('data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNDc0LjggNDc0LjgwMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDc0LjggNDc0LjgwMTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zOTYuMjgzLDI1Ny4wOTdjLTEuMTQtMC41NzUtMi4yODItMC44NjItMy40MzMtMC44NjJjLTIuNDc4LDAtNC42NjEsMC45NTEtNi41NjMsMi44NTdsLTE4LjI3NCwxOC4yNzEgICAgYy0xLjcwOCwxLjcxNS0yLjU2NiwzLjgwNi0yLjU2Niw2LjI4M3Y3Mi41MTNjMCwxMi41NjUtNC40NjMsMjMuMzE0LTEzLjQxNSwzMi4yNjRjLTguOTQ1LDguOTQ1LTE5LjcwMSwxMy40MTgtMzIuMjY0LDEzLjQxOCAgICBIODIuMjI2Yy0xMi41NjQsMC0yMy4zMTktNC40NzMtMzIuMjY0LTEzLjQxOGMtOC45NDctOC45NDktMTMuNDE4LTE5LjY5OC0xMy40MTgtMzIuMjY0VjExOC42MjIgICAgYzAtMTIuNTYyLDQuNDcxLTIzLjMxNiwxMy40MTgtMzIuMjY0YzguOTQ1LTguOTQ2LDE5LjctMTMuNDE4LDMyLjI2NC0xMy40MThIMzE5Ljc3YzQuMTg4LDAsOC40NywwLjU3MSwxMi44NDcsMS43MTQgICAgYzEuMTQzLDAuMzc4LDEuOTk5LDAuNTcxLDIuNTYzLDAuNTcxYzIuNDc4LDAsNC42NjgtMC45NDksNi41Ny0yLjg1MmwxMy45OS0xMy45OWMyLjI4Mi0yLjI4MSwzLjE0Mi01LjA0MywyLjU2Ni04LjI3NiAgICBjLTAuNTcxLTMuMDQ2LTIuMjg2LTUuMjM2LTUuMTQxLTYuNTY3Yy0xMC4yNzItNC43NTItMjEuNDEyLTcuMTM5LTMzLjQwMy03LjEzOUg4Mi4yMjZjLTIyLjY1LDAtNDIuMDE4LDguMDQyLTU4LjEwMiwyNC4xMjYgICAgQzguMDQyLDc2LjYxMywwLDk1Ljk3OCwwLDExOC42Mjl2MjM3LjU0M2MwLDIyLjY0Nyw4LjA0Miw0Mi4wMTQsMjQuMTI1LDU4LjA5OGMxNi4wODQsMTYuMDg4LDM1LjQ1MiwyNC4xMyw1OC4xMDIsMjQuMTNoMjM3LjU0MSAgICBjMjIuNjQ3LDAsNDIuMDE3LTguMDQyLDU4LjEwMS0yNC4xM2MxNi4wODUtMTYuMDg0LDI0LjEzNC0zNS40NSwyNC4xMzQtNTguMDk4di05MC43OTcgICAgQzQwMi4wMDEsMjYxLjM4MSw0MDAuMDg4LDI1OC42MjMsMzk2LjI4MywyNTcuMDk3eiIgZmlsbD0iIzAwMDAwMCIvPgoJCTxwYXRoIGQ9Ik00NjcuOTUsOTMuMjE2bC0zMS40MDktMzEuNDA5Yy00LjU2OC00LjU2Ny05Ljk5Ni02Ljg1MS0xNi4yNzktNi44NTFjLTYuMjc1LDAtMTEuNzA3LDIuMjg0LTE2LjI3MSw2Ljg1MSAgICBMMjE5LjI2NSwyNDYuNTMybC03NS4wODQtNzUuMDg5Yy00LjU2OS00LjU3LTkuOTk1LTYuODUxLTE2LjI3NC02Ljg1MWMtNi4yOCwwLTExLjcwNCwyLjI4MS0xNi4yNzQsNi44NTFsLTMxLjQwNSwzMS40MDUgICAgYy00LjU2OCw0LjU2OC02Ljg1NCw5Ljk5NC02Ljg1NCwxNi4yNzdjMCw2LjI4LDIuMjg2LDExLjcwNCw2Ljg1NCwxNi4yNzRsMTIyLjc2NywxMjIuNzY3YzQuNTY5LDQuNTcxLDkuOTk1LDYuODUxLDE2LjI3NCw2Ljg1MSAgICBjNi4yNzksMCwxMS43MDQtMi4yNzksMTYuMjc0LTYuODUxbDIzMi40MDQtMjMyLjQwM2M0LjU2NS00LjU2Nyw2Ljg1NC05Ljk5NCw2Ljg1NC0xNi4yNzRTNDcyLjUxOCw5Ny43ODMsNDY3Ljk1LDkzLjIxNnoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K') no-repeat center;
            background-size: contain;
            cursor: pointer;
            z-index: 999999;
        }
    </style>
@endsection

@section('content')
    <div class="loading">Loading&#8230;</div>
    @if (session('successMessage'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check-circle"></i> Successful!</h4>
            {{ session('successMessage') }}
        </div>
    @endif
    @if (session('failMessage'))
        <div class="alert alert-error alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-minus-circle"></i> Failed!</h4>
            {{ session('failMessage') }}
        </div>
    @endif
    <button class="btn btn-primary" id="btn_generate">Generate </button>
    <button class="btn btn-primary" id="btn_view">View</button>

    <div class="modal fade" id="modal-generate">
        <form id="form_generateRoaster" class="form-horizontal" method="post" action="/generateStudentRoster" autocomplete="off">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Generate Students' Roster</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="generateStudentRoster"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label>Grade <span class="required">*</span></label>
                                                    <select id="select_grade" class="form-control select2 percent100" multiple data-placeholder="Select Gradees for generating roaster" name="selectedGrades[]" required>
                                                        @foreach($allGrades as $grade)
                                                            <option value="{{$grade->grade_id}}">{{$grade->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span id="error_grade_generate" class="no-error">Required!</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="inputYear" class="control-label">Year <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="inputYear" placeholder="Year" name="year" min="2015" value="{{ now()->year }}" required autocomplete="off">
                                                        <span id="error_year_generate" class="no-error">Required!</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <label for="select_month">Month <span class="required">*</span></label>
                                                    <select id="select_month" class="form-control select2 " data-placeholder="Select Month" name="selectedMonth" required>
                                                        <option></option>
                                                        <option value="1">January</option>
                                                        <option value="2">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                    <span id="error_month_generate" class="no-error">Required!</span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary" id="btn_confirm_generate" value="Generate">Generate</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>

    <div class="modal fade" id="modal-view">
        <form id="form_viewRoster" class="form-horizontal" method="post" action="/studentRoster">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">View Roster Of Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="viewRoster"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group" >
                                                    <label for="select_grade_view">Grade <span class="required">*</span></label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="select_grade_view" class="form-control select2 percent100"  data-placeholder="Select a Grade" name="selectedGradeView" onchange="populateStudent(this.value)">
                                                                <option></option>
                                                                @foreach($allGrades as $grade)
                                                                    <option value="{{$grade->grade_id}}">{{$grade->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="error_grade_view" class="no-error">Required!</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group" >
                                                    <label for="select_student_view">Student <span class="required">*</span></label>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <select id="select_student_view" class="form-control select2 percent100"  data-placeholder="Select a Student" name="selectedStudentView">
                                                                <option></option>
                                                            </select>
                                                            <span id="error_student_view" class="no-error">Required!</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <div><label for="datepicker_date" class="col-sm-4 control-label">Date</label></div>
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right" id="datepicker_date" name="dateView" autocomplete="off">
                                                </div>
                                                <span id="error_date_view" class="no-error">Required!</span>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_confirm_view" value="view">View</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <div class="modal" id="modal-wait">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Please wait...Generating roster!!</h4>
                    
                </div>
                <div class="modal-body">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                
            </div>
        </div>
    </div>

    @if($rosterDetail !="")
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Students Roster Detail </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="rosterDetailTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Day Status</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="roster-list" name="roster-list">
                    @foreach($rosterDetail as $row)
                        @foreach($row->rosters as $roster)
                            <tr id="roster{{$roster['id']}}">
                                <td>{{$row->grade['name']}}</td>
                                <td>{{$row->section['name']}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$roster['date']}}</td>
                                <td>{{$row->shift['name']}}</td>
                                <td>{{$roster['is_holiday']}}</td>
                                <td>{{$roster->updated_at}}</td>
                                <td>
                                    <button class="btn btn-warning open_modal" value="{{$roster['id']}}"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger delete-row" value="{{$roster['id']}}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Day Status</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    @endif

    <div class="modal fade" id="modal-editRoster">
        <form id="form_editRoster" class="form-horizontal" method="POST" action="/updateStudentRoster">
            <input type="hidden" id="roster_id" name="roster_id">
            {{ csrf_field() }}
            {{ method_field('PUT')}}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Edit Roster Of Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="editRoster"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_grade_edit" class="control-label">Grade <span class="required">*</span></label>
                                            <input type="text" id="grade_edit" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_section_edit" class="control-label">Section <span class="required">*</span></label>
                                            <input type="text" id="section_edit" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_student_view" class="control-label">Student <span class="required">*</span></label>
                                            <input type="text" id="student_edit" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="roster_date" class="control-label">Date <span class="required">*</span></label>
                                            <input type="text" id="roster_date" class="form-control" disabled>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label for="select_shift" class="control-label">Roster Date <span class="required">*</span></label>
                                        <select id="select_shift" class="form-control select2 percent100"  data-placeholder="Select Shift" name="selectedShift">
                                            <option></option>
                                            @foreach($shifts as $shift)
                                                <option value="{{$shift->shift_id}}">{{$shift->name}} [{{$shift->start_time}} - {{$shift->end_time}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="select_dayStatus" class="control-label">Day Status <span class="required">*</span></label>
                                        <select id="select_dayStatus" class="form-control select2 percent100"  data-placeholder="Select Day Status" name="selectedDayStatus">
                                            <option></option>
                                            <option value = "C">Class</option>
                                            <option value = "H">Holiday</option>
                                            <option value = "O">Weekly Off</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_confirm_update" value="edit">Update</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>

@endsection

@section('footer')
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            
            $('#datepicker_date').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 0,
                autoclose: true,
                todayHighlight: true,
                orientation: "auto"
            });
            $('#modal-wait').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#modal-wait').modal('hide');
            $('#rosterDetailTable').DataTable({
                'paging'        : true,
                'lengthChange'  : true,
                'searching'     : true,
                'ordering'      : true,
                'info'          : true,
                'autoWidth'     : true,
                "scrollX"       : true
            });
            
            //Initialize Select2 Elements
            $('.select2').select2();
            $('.select2[multiple]').siblings('.select2-container').append('<span class="select-all"></span>');
            $('.loading').hide();
            $(document).on('click', '.select-all', function (e) {
                selectAllSelect2($(this).siblings('.selection').find('.select2-search__field'));
            });

            function selectAllSelect2(that) {
                var selectAll = true;
                var existUnselected = false;
                var id = that.parents("span[class*='select2-container']").siblings('select[multiple]').attr('id');
                var item = $("#" + id);

                item.find("option").each(function (k, v) {
                    if (!$(v).prop('selected')) {
                        existUnselected = true;
                        return false;
                    }
                });

                selectAll = existUnselected ? selectAll : !selectAll;

                item.find("option").prop('selected', selectAll).trigger('change');
            }
            
        });

        $('#btn_generate').click(function(){
            $('#form_generateRoster').trigger("reset");
            $('#modal-generate').modal('show');
        });
        $('#btn_view').click(function(){
            $('#form_viewRoster').trigger("reset");
            $('#modal-view').modal('show');
        });

        $('#btn_confirm_generate').click(function(e){
            if(!validate_generate()){
                e.preventDefault();
            }
            else{
                $('#modal-generate').modal('hide');
                $('#modal-wait').modal('show');
            }
        });

        function populateStudent(grade){
            //console.log("View");
            if($('#select_grade_view').val()!="" && $('#select_grade_view').val()!= null){
                $.get("/students/grade/"+grade, function(data){
                    $("#select_student_view").empty();
                    $('#select_student_view').append('<option></option>');
                    for(var i=0;i<data.length;i++){
                        var newStudent = $('<option value="'+data[i].student_id+'">'+data[i].name+'</option>');
                        $('#select_student_view').append(newStudent).trigger('change');
                    }
                    $('.select2').select2();
                    //console.log(data.length);
                });
            }
            
        }

        //delete roster and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete a roster. Are you sure?')){
                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteStudentRoster/' + id,
                    success: function (data) {
                        $("#roster" + id).remove();
                    },
                    error: function (data) {
                        console.error('Error:', data);
                    }
                });
            }
            
        });

        $(document).on('click','.open_modal',function(){
            var id = $(this).val();
            console.log("Clicked on edit1");
            $.get('/getStudentRosterData/'+id, function(data){
                console.log(data);
                $('#roster_id').val(id);
                $('#grade_edit').val(data.student.grade.name);
                $('#section_edit').val(data.student.section!=null?data.student.section.name:"");
                $('#student_edit').val(data.student.name);
                $('#roster_date').val(data.date);
                $('#select_shift').val(data.shift_id).trigger('change');
                $('#select_dayStatus').val(data.is_holiday).trigger('change');

                $('#modal-editRoster').modal('show');

            });  
        });
        $('#btn_confirm_update').click(function(e){
            e.preventDefault();
            var formData = {
                'id' : $('#roster_id').val(),
                'shift' : $('#select_shift').val(),
                'dayStatus': $('#select_dayStatus').val()
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'PUT',
                url: '/updateStudentRoster',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $('#modal-editRoster').modal('hide');
                    var grade_name = data.student.grade!=null ? data.student.grade['name'] : "";
                    var section_name = data.student.section!=null ? data.student.section['name']: "";
                    var rowUpdated = '<tr id="#roster"'+data.id+'>'+
                                        '<td>'+ grade_name +'</td>' +
                                        '<td>'+ section_name +'</td>' +
                                        '<td>'+ data.student['name'] +'</td>'+
                                        '<td>'+ data.date +'</td>'+
                                        '<td>'+ data.shift['name'] +'</td>'+
                                        '<td>'+ data.is_holiday +'</td>'+
                                        '<td>'+ data.updated_at +'</td>'+
                                        '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"></i></button>'+
                                        ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"></i></button></td></tr>'
                    $("#roster"+data.id).replaceWith(rowUpdated);
                }
            });
        });
        $('#btn_confirm_view').click(function(e){
            if(validate_view()){
                $('#modal-view').modal('hide');
                $('.loading').show();
            }else
                e.preventDefault();
            
        });
        function validate_generate(){
            var validated = true;
            if($('#select_branch').val().length <1 ){
                validated = false;
                $('#error_branch_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_branch_generate').removeClass('error').addClass('no-error');
            }
            if($('#inputYear').val() ==""){
                validated = false;
                $('#error_year_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_year_generate').removeClass('error').addClass('no-error');
            }
            if($('#select_month').val() ==[]){
                validated = false;
                $('#error_month_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_month_generate').removeClass('error').addClass('no-error');
            }
            return validated;
        }
        $(document).on('change','#select_branch', function(){
            if($('#select_branch').val().length<1){
                $('#error_branch_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_branch_generate').removeClass('error').addClass('no-error');
            }
        });
        $(document).on('change','#inputYear', function(){
            if($('#inputYear').val()==''){
                $('#error_year_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_year_generate').removeClass('error').addClass('no-error');
            }
        });
        $(document).on('change','#select_month', function(){
            if($('#select_month').val().length<1){
                $('#error_month_generate').removeClass('no-error').addClass('error');
            }else{
                $('#error_month_generate').removeClass('error').addClass('no-error');
            }
        });
        function validate_view(){
            var validated = true;
            if($('#select_grade_view').val().length <1 ){
                validated = false;
                $('#error_grade_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_grade_view').removeClass('error').addClass('no-error');
            }
            if($('#select_student_view').val() ==""){
                validated = false;
                $('#error_student_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_student_view').removeClass('error').addClass('no-error');
            }
            if($('#datepicker_date').val() ==[]){
                validated = false;
                $('#error_date_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_date_view').removeClass('error').addClass('no-error');
            }
            return validated;
        }
        $(document).on('change','#select_branch_view', function(){
            if($('#select_branch_view').val().length<1){
                $('#error_branch_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_branch_view').removeClass('error').addClass('no-error');
            }
        });
        $(document).on('change','#select_employee_view', function(){
            if($('#select_employee_view').val().length<1){
                $('#error_employee_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_employee_view').removeClass('error').addClass('no-error');
            }
        });
        $(document).on('change','#datepicker_date', function(){
            if($('#datepicker_date').val()==''){
                $('#error_date_view').removeClass('no-error').addClass('error');
            }else{
                $('#error_date_view').removeClass('error').addClass('no-error');
            }
        });

    </script>
@endsection