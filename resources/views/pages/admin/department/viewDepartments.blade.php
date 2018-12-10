@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="loading">Loading&#8230;</div>
    <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">
    
    <div class="modal fade" id="modal-add">
        <form id="form_addDepartment" class="form-horizontal">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Department</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                    <div class="row roundPadding20" id="addNewDepartment"> 
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="inputDepartmentId" class="control-label">Department ID <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="inputDepartmentId" placeholder="Department ID" name="department_id" required>
                                        <span id="error_msg_id" class="no-error">Department ID already exists!</span>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="department_name" required> <!--data-validation="length" data-validation-length="min4"-->
                                        <span id="error_name" class="no-error"></span>
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
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->"
        </form>
    </div>
    <!-- /.modal -->

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Departments 
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
        </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="departmentTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="departments-list" name="departments-list">
                @foreach($departments as $dept)
                    <tr id="department{{$dept->department_id}}">
                        <td>{{$dept->department_id}}</td>
                        <td>{{$dept->name}}</td>
                        <td>
                            <button class="btn btn-warning open_modal" value="{{$dept->department_id}}"><i class="fa fa-edit"> </i></button> 
                            <button class="btn btn-danger delete-department" value="{{$dept->department_id}}"><i class="fa fa-trash"> </i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

@endsection

@section('footer')
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
  $.validate({
    lang: 'en'
  });
</script>
<script>
    var dept_datatable;
    $(document).ready(function() {
        dept_datatable = $('#departmentTable').DataTable({
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true
        });
        $('.loading').hide();
    });

    var original_department_id;
    var state;
    var company_id;
    //Opening Add Modal
    $('#btn_add').click(function(){
        $('#inputDepartmentId').prop('disabled',false);

        $('.error').removeClass('error').addClass('no-error');
        
        $('#btn_confirm').val("add");
        $('#form_addDepartment').trigger("reset");
        $('#modal-title').text('Add Department');
        $('#btn_confirm').text("Add");
        $('#modal-add').modal('show');
        state="add";
    });
    //Opening Edit Modal
    $(document).on('click', '.open_modal', function(){
        state="update";
        $('.error').removeClass('error').addClass('no-error');
        var department_id = $(this).val();
        $.get('/getDepartmentById/' + department_id, function (data) {
            //success data
            original_department_id = department_id;
            $('#inputDepartmentId').val(data.department_id).prop('disabled',true);
            $('#inputName').val(data.name);
            $('#btn_confirm').val("update");
            $('#btn_confirm').text("Update");
            $('#modal-title').text('Edit Department');
            $('#modal-add').modal('show');
        }); 
    });

    //delete department and remove it from list
    $(document).on('click','.delete-department',function(){
        if(confirm('You are about to delete a department. Are you sure?')){
            var department_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "DELETE",
                url: '/deleteDepartment/' + department_id,
                success: function (data) {
                    $("#department" + department_id).remove();
                },
                error: function (data) {
                    alert("Some error occurred! \nPlease Try again!")
                    console.error('Error:', data.responseJSON);
                }
            });
        }
        
    });
    
    
    var old_department_id;
    //Detecting change on edit
    $(document).on('focusin', '#inputDepartmentId', function(){
        $(this).data('val', $(this).val());
        }).on('change','#inputDepartmentId', function(){
        var current = $(this).val();
        if(state=="update"){
            if($('[id=department'+original_department_id+']').length>0 && original_department_id !=current && $('[id=department'+current+']').length>0){
                $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already exists!');
            }else{
                $('#error_msg_id').removeClass('error').addClass('no-error');
            }
        }
        else if(state=="add"){
            if($('[id=department'+current+']').length>0){
                $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already exists!');
            }
            else{
                $('#error_msg_id').removeClass('error').addClass('no-error');
            }
        }
    });
    
    //create new product / update existing product
    $("#btn_confirm").click(function (e) {
        if(validate()){    
            var type = "POST"; //for creating new resource
            var department_id = $('#inputDepartmentId').val();
            var url = '/addDepartment'; // by default add department
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                department_id       : $('#inputDepartmentId').val(),
                name                : $('#inputName').val(),
                company_id          : $('#inputCompanyId').val()
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addDepartment';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateDepartment/' + original_department_id;
            }
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    var department = '<tr id="department' + data.department_id + '"><td>' + data.department_id + '</td><td>' + data.name + '</td>';
                    department += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.department_id + '"><i class="fa fa-edit"> </i></button>';
                    department += ' <button class="btn btn-danger btn-delete delete-department" value="' + data.department_id + '"><i class="fa fa-trash"> </i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#departments-list').prepend(department);
                    }else{ //if user updated an existing record
                        $("#department" + original_department_id).replaceWith( department );
                    }
                    $('#form_department').trigger("reset");
                    $('#modal-add').modal('hide');
                },
                error: function (data) {
                    alert('Error: Please Try Again!');
                    console.log('Error:', data);
                }
            });
        }    
    });
    function validate(){
        var validated = true;
        if($('#inputDepartmentId').val()==''){
            validated = false;
            $('#error_msg_id').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var current = $('#inputDepartmentId').val();
            if(state=="update"){
                if($('[id=department'+original_department_id+']').length>0 && original_department_id !=current && $('[id=department'+current+']').length>0){
                    $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already exists!');
                    validated = false;
                }else{
                    $('#error_msg_id').removeClass('error').addClass('no-error');
                }
            }
            else if(state=="add"){
                if($('[id=department'+current+']').length>0){
                    $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already exists!');
                    validated = false;
                }
                else{
                    $('#error_msg_id').removeClass('error').addClass('no-error');
                }
            }
        }
        if($('#inputName').val()==''){
            validated = false;
            $('#error_name').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_name').removeClass('error').addClass('no-error');
        }
        return validated;
    }
    $('#inputName').keyup(function(){
        if($('#inputName').val()!='')
            $('#error_name').removeClass('error').addClass('no-error');
        else
            $('#error_name').removeClass('no-error').addClass('error');
    });
</script>
@endsection