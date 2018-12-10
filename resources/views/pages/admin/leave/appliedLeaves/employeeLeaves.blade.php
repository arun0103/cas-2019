@extends('layouts.master')
@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="loading">Loading&#8230;</div>
    <div>
        <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">
        <input type="hidden" id="inputUserId" disabled value="{{Session::get('user_id')}}">
    </div>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Applied Leaves of Employees
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="appliedLeavesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Leave ID</th>
                    <th>Applied By</th>
                    <th>Leave Type</th>
                    <th>Reason</th>
                    <th>#Days</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Day Part</th>
                    <th>Approved By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="appliedLeaves-list" name="leaveMaster-list">
                @foreach ($appliedLeaves as $al)
                <tr id="appliedLeave{{$al->id}}">
                    <td>{{$al->leave_id}}</td>
                    <td>{{$al->employee->name}}</td>
                    <td>{{$al->leave->name}}</td>
                    <td>{{$al->remarks}}</td>
                    <td>{{$al->applied_days}}</td>
                    <td>{{$al->leave_from}}</td>
                    <td>{{$al->leave_to}}</td>
                    <td>{{$al->day_part}}</td>
                    <td>{{$al->approved_by}}</td>
                    <td>
                        <button class="btn btn-warning open_modal" value="{{$al->id}}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger delete-row" value="{{$al->id}}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Leave ID</th>
                    <th>Applied By</th>
                    <th>Leave Type</th>
                    <th>Reason</th>
                    <th>#Days</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Day Part</th>
                    <th>Approved By</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="modal fade" id="modal-add">
        <form id="form_applyLeave" class="form-horizontal">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Apply Leave</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="applyLeave"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_branch" class="control-label">Branch <span class="required">*</span></label>
                                            <select id="select_branch" class="form-control select2" data-placeholder="Select Branch" name="selectedBranch" onchange="populateEmployee(this.value)">
                                                <option></option>
                                                @foreach($companyBranches as $branch)
                                                    <option value="{{$branch->branch_id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                            <span id="error_branch" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_employee" class="control-label">Employee <span class="required">*</span></label>
                                            <select id="select_employee" class="form-control select2" data-placeholder="Select Employee" name="selectedEmployee" onchange="populateLeaveTypes(this.value)">
                                            </select>
                                            <span id="error_employee" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_leaveType" class="control-label">Leave Type <span class="required">*</span></label>
                                            <select id="select_leaveType" class="form-control select2" data-placeholder="Select Leave Type" name="selectedLeaveType" onchange="checkLeaveBalance(this.value)">
                                            </select>
                                            <span id="error_leaveType" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveStatus_add" class="control-label">Status <span class="required">*</span></label>
                                            <input type="text" class="form-control inputLeaveStatus" id="inputLeaveStatus_add" placeholder="Select Leave First" name="leaveStatus" disabled>
                                            <span id="error_status" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveDays" class="control-label">Leave Days <span class="required">*</span></label>
                                            <input type="number" class="form-control" id="inputLeaveDays" placeholder="Leave Days" name="leaveDays" min="1">
                                            <span id="error_leaveDays" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_leavePart" class="control-label">Day Part <span class="required">*</span></label>
                                            <select id="select_leavePart" class="form-control select2" data-placeholder="Select Day Part" name="selectedLeavePart">
                                                <option></option>
                                                <option value=3>Full Day</option>
                                                <option value=1>1st Half</option>
                                                <option value=2>2nd Half</option>
                                            </select>
                                            <span id="error_leavePart" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <div><label for="datepicker_from" class="control-label">From <span class="required">*</span></label></div>
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right" id="datepicker_from" autocomplete="off" onchange="fromDateSelected(this.value)">
                                                    <span id="error_leaveFrom" class="no-error">Required!</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <div><label for="datepicker_to" class="control-label">To <span class="required">*</span></label></div>
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right" data-date-format="yyyy-mm-dd" id="datepicker_to" autocomplete="off" >
                                                    <span id="error_leaveTo" class="no-error">Required!</span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Remarks</label>
                                        <textarea class="form-control" id="inputRemarks" placeholder="" name="remarks"></textarea>            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_confirm" value="Add">Add</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <div class="modal fade" id="modal-edit">
        <form id="form_edit_applyLeave" class="form-horizontal" method="post" action="/edit_applyLeave">
        <input type="hidden" id="appliedLeave_id" name="appliedLeave_id">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Edit Applied Leave</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="edit_applyLeave"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_edit_branch" class="control-label">Branch</label>
                                            <input type="text" disabled id="input_edit_branch" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="input_edit_employee" class="control-label">Employee</label>
                                            <input type="text" disabled id="input_edit_employee" class="form-control">
                                            <input type="hidden" disabled id="input_edit_employee_id" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_leaveType_edit" class="control-label">Leave Type</label>
                                            <select id="select_leaveType_edit" class="form-control select2" data-placeholder="Select Leave Type" name="selectedLeaveType" onchange="checkLeaveBalance_edit(this.value)">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveStatus_edit" class="control-label">Status</label>
                                            <input type="text" class="form-control" id="inputLeaveStatus_edit" placeholder="Select Leave First" name="leaveStatus" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveDays_edit" class="control-label">Leave Days</label>
                                            <input type="number" class="form-control" id="inputLeaveDays_edit" placeholder="Leave Days" name="leaveDays" min="1" onchange="fromDateSelected_edit($('#datepicker_from_edit').val());">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="select_leavePart_edit" class="control-label">Day Part</label>
                                            <select id="select_leavePart_edit" class="form-control select2" data-placeholder="Select Day Part" name="selectedLeavePart">
                                            <option></option>
                                            <option value=3>Full Day</option>
                                            <option value=1>1st Half</option>
                                            <option value=2>2nd Half</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <div><label for="datepicker_from" class="control-label">From</label></div>
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <input type="text" class="form-control pull-right" id="datepicker_from_edit" autocomplete="off" onchange="fromDateSelected_edit(this.value)">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <div><label for="datepicker_to" class="control-label">To</label></div>
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <input type="text" class="form-control pull-right" data-date-format="yyyy-mm-dd" id="datepicker_to_edit" autocomplete="off" >
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Remarks</label>
                                        <textarea class="form-control" id="inputRemarks_edit" placeholder="" name="remarks"></textarea>            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_confirm_edit" value="Update">Update</button>
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
    <script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            $('.select2').select2();
            //Initialize Datatables
            $('#appliedLeavesTable').DataTable({
                'paging':       true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'paging'      : true,
                'info'        : true,
                'autoWidth'   : true,
                "scrollX": true
            });
            $('.date').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 0,
                autoclose: true,
                todayHighlight: true,
                orientation: "auto",
                
            });
            $( "#datepicker_to" ).datepicker({showOn : "off"});
            $('.loading').hide();
        });
        
        //Opening Add Modal
        $('#btn_add').click(function(){
            $('#error_msg_id').removeClass('error').addClass('no-error');
            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Apply Leave');
            $('#form_applyLeave').trigger("reset");
            $('#select_leavePart').val([]).trigger("change");
            $('#select_leaveType').val([]).trigger("change");
            $('#select_employee').val([]).trigger("change");
            $('#select_branch').val([]).trigger("change");
            
            $('#modal-add').modal('show');
        });
        

        //delete applied leave and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete an applied leave. Are you sure?')){
                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteAppliedLeave/' + id,
                    success: function (data) {
                        $("#appliedLeave" + id).remove();
                    },
                    error: function (data) {
                        console.error('Error:', data);
                    }
                });
            }
            
        });
        var leave_days = 0;
        $(document).on('click', '.open_modal', function(){
            leave_days = 0;
            var id = $(this).val();
            $('#appliedLeave_id').val(id);
            $('#select_leaveType_edit').empty();
            
            $.get('/getAppliedLeave/'+id, function(data){
                $('#select_leaveType_edit').append('<option></option>').trigger('change');
                $.each( data.companyLeaves, function( key, value ) {
                    var newLeaveType = $('<option value="'+value.leave_id+'">'+value.leave_master.name+'</option>');
                    $('#select_leaveType_edit').append(newLeaveType).trigger('change');

                });
                
                console.log(data);
                $('#input_edit_branch').val(data.branch_name).trigger('change');
                $('#input_edit_branch').prop('disabled',true);
                $('#input_edit_employee').val(data.emp_name).trigger('change');
                $('#input_edit_employee_id').val(data.emp_id).trigger('change');
                $('#input_edit_employee').prop('disabled',true);

                $('#select_leaveType_edit').val(data.leave_id).change();
                $('#inputLeaveDays_edit').val(data.leave_days).change();
                $('#select_leavePart_edit').val(data.day_part).change();
                $('#datepicker_from_edit').val(data.leave_from).change();
                $('#datepicker_to_edit').val(data.leave_to).change();
                $('#inputRemarks_edit').val(data.remarks).change();
                
                leave_days = data.leave_days;
                $('#modal-edit').modal('show');
            });
            
            
        });
        
        //Detecting change on edit
        $(document).on('focusin', '#inputLeaveId', function(){
                $(this).data('val', $(this).val());
            }).on('change','#inputLeaveId', function(){
                var current = $(this).val();
                if(state=="update"){
                    if($('[id=leaveMaster'+original_leave_master_id+']').length>0 && original_leave_master_id !=current && $('[id=leaveMaster'+current+']').length>0){
                        $('#error_msg_id').removeClass('no-error').addClass('error');
                    }
                    else{
                        $('#error_msg_id').removeClass('error').addClass('no-error');
                    }
                }else if(state=="add"){
                    if($('[id=leaveMaster'+current+']').length>0){
                        $('#error_msg_id').removeClass('no-error').addClass('error');
                    }
                    else{
                        $('#error_msg_id').removeClass('error').addClass('no-error');
                    }
                }
            
        });
        function validate(){
            var validated = true;
            console.log($('#select_branch').val());
            if($('#select_branch').val()==null || $('#select_branch').val()=='' || $('#select_branch').val()==[]){
                validated = false;
                $('#error_branch').removeClass('no-error').addClass('error');
                $('#error_employee').removeClass('no-error').addClass('error');
                $('#error_leaveType').removeClass('no-error').addClass('error');
            }else{
                $('#error_branch').removeClass('error').addClass('no-error');
                if($('#select_employee').val()==[]){
                    validated = false;
                    $('#error_employee').removeClass('no-error').addClass('error');
                }else{
                    $('#error_employee').removeClass('error').addClass('no-error');
                }
                if($('#select_leaveType').val()==[]){
                    validated = false;
                    $('#error_leaveType').removeClass('no-error').addClass('error');
                }else{
                    $('#error_leaveType').removeClass('error').addClass('no-error');
                }
            }
            if($('#select_leavePart').val()==[]||$('#select_leavePart').val()==null||$('#select_leavePart').val()==''){
                validated = false;
                $('#error_leavePart').removeClass('no-error').addClass('error');
            }else{
                $('#error_leavePart').removeClass('error').addClass('no-error');
            }
            
            if($('#inputLeaveDays').val()<1){
                validated = false;
                $('#error_leaveDays').removeClass('no-error').addClass('error');
            }else{
                $('#error_leaveDays').removeClass('error').addClass('no-error');
            }
            if($('#datepicker_from').val()==''){
                validated = false;
                $('#error_leaveFrom').removeClass('no-error').addClass('error');
            }else{
                $('#error_leaveFrom').removeClass('error').addClass('no-error');
            }
            if($('#datepicker_to').val()==''){
                validated = false;
                $('#error_leaveTo').removeClass('no-error').addClass('error');
            }else{
                $('#error_leaveTo').removeClass('error').addClass('no-error');
            }
            return validated;
        }
        
        //create new product / update existing product
        $("#btn_confirm").click(function (e) {
            if(validate()){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                e.preventDefault(); 
                
                var formData = {
                    leave_id                : $('#select_leaveType').val(),
                    emp_id                  : $('#select_employee').val(),
                    company_id              : $('#inputCompanyId').val(),
                    applied_days            : $('#inputLeaveDays').val(),
                    posted_days             : $('#inputLeaveDays').val(),
                    leave_from              : $('#datepicker_from').val(),
                    leave_to                : $('#datepicker_to').val(),
                    day_part                : $('#select_leavePart').val(),
                    comp_off_date_1         : null,
                    comp_off_date_2         : null,
                    remarks                 : $('#inputRemarks').val(),
                    status                  : 1,
                    approved_by             : $('#inputUserId').val(), 
                }
                $.ajax({
                    type: 'POST',
                    url: '/applyLeave',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        var newRow = '<tr id="appliedLeave' + data.id + '">'
                                +'<td>' + data.leave_id + '</td>'
                                +'<td>' + data.employee['name'] + '</td>'
                                +'<td>' + data.leave['name']+'</td>'
                                +'<td>' + data.remarks+'</td>'
                                +'<td>' + data.applied_days+'</td>'
                                +'<td>' + data.leave_from+'</td>'
                                +'<td>' + data.leave_to+'</td>'
                                +'<td>' + data.day_part+'</td>'
                                +'<td>' + data.approved_by+'</td>';
                        newRow += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"></i></button>';
                        newRow += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                        $('#appliedLeaves-list').prepend(newRow);
                        
                        $('#form_applyLeave').trigger("reset");
                        $('#modal-add').modal('hide');
                    },
                    error: function (data) {
                        alert('Error: '+JSON.stringify(data));
                        console.log('Error:', JSON.stringify(data));
                    }
                });
            }
        });
        $('#btn_confirm_edit').click(function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            
            var formData = {
                id                      : $('#appliedLeave_id').val(),
                leave_id                : $('#select_leaveType_edit').val(),
                applied_days            : $('#inputLeaveDays_edit').val(),
                posted_days             : $('#inputLeaveDays_edit').val(),
                leave_from              : $('#datepicker_from_edit').val(),
                leave_to                : $('#datepicker_to_edit').val(),
                day_part                : $('#select_leavePart_edit').val(),
                comp_off_date_1         : null,
                comp_off_date_2         : null,
                remarks                 : $('#inputRemarks_edit').val(),
                status                  : 1,
                approved_by             : $('#inputUserId').val(), 
            } 
            $.ajax({
                type: 'PUT',
                url: '/updateLeave',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var newRow = '<tr id="appliedLeave' + data.id + '">'
                            +'<td>' + data.leave_id + '</td>'
                            +'<td>' + data.employee['name'] + '</td>'
                            +'<td>' + data.leave['name']+'</td>'
                            +'<td>' + data.remarks+'</td>'
                            +'<td>' + data.applied_days+'</td>'
                            +'<td>' + data.leave_from+'</td>'
                            +'<td>' + data.leave_to+'</td>'
                            +'<td>' + data.day_part+'</td>'
                            +'<td>' + data.approved_by+'</td>';
                    newRow += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"></i></button>';
                    newRow += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    $('#appliedLeave'+data.id).replaceWith(newRow);
                    
                    $('#form_edit_applyLeave').trigger("reset");
                    $('#modal-edit').modal('hide');
                },
                error: function (data) {
                    alert('Error: '+JSON.stringify(data));
                    console.log('Error:', JSON.stringify(data));
                }
            });
        });
        function populateEmployee(branch){
            if($('#select_branch').val() !="" && $('#select_branch').val() != null){
                $('.loading').show();
                //var selectedBranch = $("#select_branch_view option:selected").val();
                console.log("Branch selected = "+branch);
                $.get("/employees/branch/"+branch, function(data){
                    console.log(data);
                    $("#select_employee").empty();
                    $("#select_employee").append($('<option></option>')).trigger('change');
                    //alert(data.length);
                    for(var i=0;i<data.length;i++){
                        var newEmployee = $('<option value="'+data[i].employee_id+'">'+data[i].name+'</option>');
                        $('#select_employee').append(newEmployee).trigger('change');
                    }
                });
                $('.loading').hide();
            }
        }
        
        function populateLeaveTypes(emp_id){
            if($('#select_employee').val()!="" && $('#select_employee').val()!= null){
                $('.loading').show();
                $.get("/employee/leaveTypes/"+emp_id, function(data){
                    $("#select_leaveType").empty();
                    $("#select_leaveType").append($('<option></option>')).trigger('change');
                    //alert(data.length);
                    for(var i=0;i<data.length;i++){
                        var newLeaveType = $('<option value="'+data[i].leave_id+'">'+data[i].leave_master.name+'</option>');
                        $('#select_leaveType').append(newLeaveType).trigger('change');
                    }
                    //$('#select_leaveTypes').val("");
                });
                $('.loading').hide();
            }
        }
        function checkLeaveBalance(leave_id){
            if($('#select_leaveType').val() != "" && $('#select_leaveType').val() != null){
                var emp_id = $('#select_employee').val();
                
                $.get('/employee/leaveStatus/'+emp_id+'/'+leave_id, function (data){
                    $('#inputLeaveStatus_add').val(data.used+" used out of "+data.total).trigger('change');
                    if(data.used == data.total){
                        alert("You have used maximum number of leave days allowed ("+data.total+")...\n Please select different leave type");
                        $('#select_leaveType').val("").change();
                        $('#inputLeaveStatus_add').val("").change();
                    }
                    $('#inputLeaveDays').prop('max',data.total-data.used);
                });
            }
        }
        function checkLeaveBalance_edit(leave_id){
            if($('#select_leaveType_edit').val() != "" && $('#select_leaveType_edit').val() != null){
                var emp_id = $('#input_edit_employee_id').val();
                console.log("leave : "+leave_id);
                if(emp_id != null && emp_id != ""){
                    $.get('/employee/leaveStatus/'+emp_id+'/'+leave_id, function (data){
                        console.log(data);
                        $('#inputLeaveStatus_edit').val(data.used+" used out of "+data.total).trigger('change');
                        // if(data.used == data.total){
                        //     alert("You have used maximum number of leave days allowed.. Please select different leave type");
                        // }
                        $('#inputLeaveDays_edit').prop('max',data.total-data.used + leave_days );
                        
                    });
                }
            }
        }
        $(document).on('change','#inputLeaveDays',function(){
            $('#datepicker_from').val([]);
            $('#datepicker_to').val([]);   
        });
        $(document).on('change','#inputLeaveDays_edit',function(){
            $('#datepicker_from_edit').val([]);
            $('#datepicker_to_edit').val([]);   
        });
        
        function fromDateSelected(fromDate){
            //console.log("From Date : "+fromDate);
            if($('#inputLeaveDays').val()!=""){
                var days = parseInt($('#inputLeaveDays').val());
                //console.log(days);
                if(days>1){
                    var fromDate = new Date($('#datepicker_from').val());
                    
                    fromDate.setDate(fromDate.getDate()+days-1); 
                    fromDate.toString("yyyy-mm-dd");
                    
                    $('#datepicker_to').datepicker('setDate',fromDate);
                    //$( "#datepicker_to" ).datepicker( "option", "dateFormat", "yyyy-mm-dd" ).trigger('change');

                }else{
                    $('#datepicker_to').val(fromDate).trigger('change');
                }
            }
        }
        function fromDateSelected_edit(fromDate){
            //console.log("From Date : "+fromDate);
            if($('#inputLeaveDays_edit').val()!=""){
                var days = parseInt($('#inputLeaveDays_edit').val());
                //console.log(days);
                if(days>1){
                    var fromDate = new Date($('#datepicker_from_edit').val());
                    
                    fromDate.setDate(fromDate.getDate()+days-1); 
                    fromDate.toString("yyyy-mm-dd");
                    console.log(fromDate);

                    $('#datepicker_to_edit').datepicker('setDate',fromDate);
                    //$( "#datepicker_to" ).datepicker( "option", "dateFormat", "yyyy-mm-dd" ).trigger('change');

                }else{
                    $('#datepicker_to_edit').datepicker('setDate',fromDate);
                }
            }
        }

    </script>
@endsection