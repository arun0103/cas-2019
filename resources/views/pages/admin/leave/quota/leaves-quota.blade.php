@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="loading">Loading&#8230;</div>
    <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Employee Leaves Quota
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <div class="box-body">
            <table id="leaveQuotaTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Employee</th>
                        <th>Leave</th>
                        <th>Alloted</th>
                        <th>Used</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveQuota-list" name="leaveQuota-list">
                    @foreach($leaveQuotas as $lq)
                        <tr id="lq{{$lq->id}}">
                            <td>{{$lq->branch->name}}</td>
                            <td>{{$lq->employee->name}}</td>
                            <td>{{$lq->leaveMaster->name}}</td>
                            <td>{{$lq->alloted_days}}</td>
                            <td>{{$lq->used_days}}</td>
                            <td>
                                <button class="btn btn-warning open_modal" value="{{$lq->id}}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger delete-row" value="{{$lq->id}}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Branch</th>
                        <th>Employee</th>
                        <th>Leave</th>
                        <th>Alloted</th>
                        <th>Used</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-add">
        <form id="form_addLeaveQuota" class="form-horizontal">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Leave Quota</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="addNewLeaveMaster"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_branch">Branch <span class="required">*</span></label>
                                            <select id="select_branch" class="form-control select2 percent100" data-placeholder="Select Branch" name="selectedBranch" onchange="populateLeaves(this.value);populateEmployee(this.value)" required>
                                                <option></option>
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->branch_id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                            <span id="error_branch" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_leave">Leave Type <span class="required">*</span></label>
                                            <select id="select_leave" class="form-control select2 " data-placeholder="Select Leave" name="selectedLeave" required>
                                                <option></option>
                                            </select>
                                            <span id="error_leave" class="no-error">Required!</span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_employee">Employee(s) <span class="required">*</span></label>
                                            <select id="select_employee" class="form-control select2 " multiple data-placeholder="Select Employee(s)" name="selectedEmployees[]" required>
                                                <option></option>
                                            </select>
                                            <span id="error_employee" class="no-error">Required!</span>
                                        </div> 
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveQuota" class="control-label">Leave Quota <span class="required">*</span></label>
                                            <input type="number" class="form-control" id="inputLeaveQuota" placeholder="Max Leave Days allowed" name="leaveQuota" min="1" required>
                                            <span id="error_leaveQuota" class="no-error">Required!</span>
                                        </div>
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
            </div>
        </form>
    </div>
    <div class="modal fade" id="modal-edit">
        <form id="form_editLeaveQuota" class="form-horizontal">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Edit Leave Quota</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="editLeaveMaster"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label>Branch <span class="required">*</span></label>
                                            <input type="text" class="form-control" disabled id="input_branch_name">
                                            <input type="hidden" class="form-control" disabled id="input_branch_id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="select_leave_edit">Leave Type <span class="required">*</span></label>
                                            <input type="text" class="form-control" disabled id="input_leave_name">
                                            <input type="hidden" class="form-control" disabled id="input_leave_id">
                                            <span id="error_leave_edit" class="no-error">Required!</span>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label>Employee <span class="required">*</span></label>
                                            <input type="text" class="form-control" disabled id="input_employee_name">
                                            <input type="hidden" class="form-control" disabled id="input_employee_id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLeaveQuota_edit" class="control-label">Leave Quota <span class="required">*</span></label>
                                            <input type="number" class="form-control" id="inputLeaveQuota_edit" placeholder="Max Leave Days allowed" name="leaveQuota" min="1" required>
                                            <span id="error_leaveQuota_edit" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_confirm_edit" value="update">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

@section('footer')
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            //Initialize Select2 Elements
            $('.select2').select2({
                allowClear: true
            });
            //Initialize Datatables
            $('#leaveQuotaTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                "scrollX"     : true,
            });

            $('.loading').hide();
        });
        function populateEmployee(branch){
            if(branch != []){
                $('#select_employee').empty();
                $.get('/employees/branch/'+branch, function(data){
                    $.each(data, function(key,val){
                        $('#select_employee').append('<option value="'+val.employee_id+'">'+val.name+'</option');
                    });
                });
            }
        }
        function populateLeaves(branch){
            if(branch != []){
                $('#select_leave').empty();
                $.get('/admin/getBranchLeaves/'+branch, function(data){
                    console.log(data);
                    $('#select_leave').empty();
                    $('#select_leave').append('<option></option>')
                    $.each(data,function(key,value){
                        $('#select_leave').append('<option value="'+value.leave_id+'">'+value.leave_name+'</option>');
                    });
                    //$('#select_leave').select2();
                });
            }
        }
        var original_leave_id;
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            $('.error').removeClass('error').addClass('no-error');
            original_leave_id = $(this).val();
            $.get('/getLeaveQuotaById/' + original_leave_id, function (data) {
                $('#input_branch_name').val(data.branch.name);
                $('#input_branch_id').val(data.branch.branch_id);
                $('#input_employee_name').val(data.employee.name);
                $('#input_employee_id').val(data.employee.employee_id);
                $('#input_leave_id').val(data.leave_id);
                $('#input_leave_name').val(data.leave_master.name);
                $('#select_leave_edit').val(data.leave_id).trigger('change');
                $('#inputLeaveQuota_edit').val(data.alloted_days);
                
                $('#modal-title').text('Edit Leave Quota');
                $('#modal-edit').modal('show');
            }); 
        });
        //Opening Add Modal
        $('#btn_add').click(function(){
            state="add";
        
            $('.error').removeClass('error').addClass('no-error');

            $('#select_branch').val([]).trigger('change');
            $('#select_leave').val([]).trigger('change');
            $('#select_employee').val([]).trigger('change');

            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Add Leave Quota');
            $('#form_addLeaveQuota').trigger("reset");
            $('#modal-add').modal('show');
        });
        $('#btn_confirm_edit').click(function(e){
            if(validate_edit()){
                var formData = {
                    company_id              : $('#inputCompanyId').val(),
                    branch_id               : $('#input_branch_id').val(),
                    leave_id                : $('#input_leave_id').val(),
                    employees               : $('#input_employee_id').val(),
                    alloted_days            : $('#inputLeaveQuota_edit').val()
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "PUT",
                    url: '/updateLeaveQuota/' + original_leave_id,
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        var newRow = '<tr id="lq' + data.id + '">'
                            +'<td>' + data.branch.name + '</td>'
                            +'<td>' + data.employee['name'] + '</td>'
                            +'<td>' + data.leave_master['name']+'</td>'
                            +'<td>' + data.alloted_days+'</td>'
                            +'<td>' + data.used_days+'</td>';
                        newRow += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"></i></button>';
                        newRow += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                        $('#lq' + original_leave_id).replaceWith( newRow );
                        $('#modal-edit').modal('hide');
                    },
                    error: function (data) {
                        alert("Error: Please Refresh and Try again!");
                    }
                }); 
            }
        });
        
        //create new product / update existing product
        $("#btn_confirm").click(function (e) {
            if(validate()==true){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = {
                    company_id              : $('#inputCompanyId').val(),
                    branch_id               : $('#select_branch').val(),
                    leave_id                : $('#select_leave').val(),
                    employees               : $('#select_employee').val(),
                    alloted_days            : $('#inputLeaveQuota').val()
                }
                $.ajax({
                    type: "POST",
                    url: '/addLeaveQuota',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        $.each(data.data, function(key,val){
                            var newRow = '<tr id="lq' + val.id + '">'
                                +'<td>' + val.branch.name + '</td>'
                                +'<td>' + val.employee['name'] + '</td>'
                                +'<td>' + val.leave_master['name']+'</td>'
                                +'<td>' + val.alloted_days+'</td>'
                                +'<td>' + val.used_days+'</td>';
                            newRow += '<td><button class="btn btn-warning btn-detail open_modal" value="' + val.id + '"><i class="fa fa-edit"></i></button>';
                            newRow += ' <button class="btn btn-danger btn-delete delete-row" value="' + val.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                            $('#leaveQuota-list').prepend(newRow);
                        });
                        alert(data.success + ' out of '+data.total + ' added!');
                        
                        $('#form_addLeaveQuota').trigger("reset");
                        $('#modal-add').modal('hide');
                    },
                    error: function (data) {
                        alert("Error: Please Refresh and Try Again!");
                    }
                });     
            }
        });
        //delete department and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete a Leave quota of an Employee. Are you sure?')){
                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteLeaveQuota/' + id,
                    success: function (data) {
                        $("#lq" + id).remove();
                    },
                    error: function (data) {
                        alert("Something went wrong! \nPlease try again after some time!")
                        console.error('Error:', data);
                    }
                });
            }
            
        });
        function validate_edit(){
            var validated = true;
            if($('#select_leave_edit').val()==[]){
                validated = false;
                $('#error_leave_edit').removeClass('no-error').addClass('error');
            }else{
                $('#error_leave_edit').removeClass('error').addClass('no-error');
            }
            if($('#inputLeaveQuota_edit').val()==[]){
                validated = false;
                $('#error_leaveQuota_edit').removeClass('no-error').addClass('error');
            }else{
                $('#error_leaveQuota_edit').removeClass('error').addClass('no-error');
            }
            return validated;
        }

        function validate(){
            var validated = true;
            if($('#select_branch').val()==[]){
                validated = false;
                $('#error_branch').removeClass('no-error').addClass('error');
            }else{
                $('#error_branch').removeClass('error').addClass('no-error');
            }
            if($('#select_leave').val()==[]){
                validated = false;
                $('#error_leave').removeClass('no-error').addClass('error');
            }else{
                $('#error_leave').removeClass('error').addClass('no-error');
            }
            if($('#select_employee').val().length<1){
                validated = false;
                $('#error_employee').removeClass('no-error').addClass('error');
            }else{
                $('#error_employee').removeClass('error').addClass('no-error');
            }
            if($('#inputLeaveQuota').val()==[]){
                validated = false;
                $('#error_leaveQuota').removeClass('no-error').addClass('error');
            }else{
                $('#error_leaveQuota').removeClass('error').addClass('no-error');
            }
            return validated;
        }
        $(document).on('change','#select_branch',function(){
            $('#error_branch').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#select_leave',function(){
            $('#error_leave').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#select_employee',function(){
            $('#error_employee').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#inputLeaveQuota',function(){
            $('#error_leaveQuota').removeClass('error').addClass('no-error');
        });

        $(document).on('change','#select_leave_edit',function(){
            $('#error_leave_edit').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#inputLeaveQuota_edit',function(){
            $('#error_leaveQuota_edit').removeClass('error').addClass('no-error');
        });
    </script>
@endsection