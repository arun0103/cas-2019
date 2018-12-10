@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="loading">Loading&#8230;</div>
    <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Designations
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <div class="box-body">
            <table id="designationTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Designation ID</th>
                    <th>Designation Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="designations-list" name="designations-list">
            @foreach($designations as $designation)
                <tr id='designation{{$designation->designation_id}}'>
                    <td>{{$designation->designation_id}}</td>
                    <td>{{$designation->name}}</td>
                    <td>
                        <button class="btn btn-warning open_modal" value="{{$designation->designation_id}}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger delete-row" value="{{$designation->designation_id}}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Designation ID</th>
                    <th>Designation Name</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-add">
        <form id="form_addDesignation" class="form-horizontal" >
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Designation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputDesignationId" class="control-label">Designation ID <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputDesignationId" placeholder="Designation ID" name="designation_id">
                                    <span id="error_msg_id" class="no-error">ID already Exists!</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Designation Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Name" name="designation_name">
                                    <span id="error_name" class="no-error">Required!</span>
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
@endsection

@section('footer')
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#designationTable').DataTable({
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true
        });
        $('.loading').hide();
    });
  
    var original_designation_id;
    var state;
    var company_id;
    //Opening Add Modal
    $('#btn_add').click(function(){
        state="add";
        $('#inputDesignationId').prop('disabled',false);

        $('.error').removeClass('error').addClass('no-error');
        $('#form_addDesignation').trigger("reset");
        $('#btn_confirm').val("add");
        $('#btn_confirm').text("Add");
        $('#modal-title').text('Add Designation');
        $('#modal-add').modal('show');    
    });
    //Opening Edit Modal
    $(document).on('click', '.open_modal', function(){
        state="update";
        $('#inputDesignationId').prop('disabled',true);
        $('.error').removeClass('error').addClass('no-error');
        var designation_id = $(this).val();
        $.get('/getDesignationById/' + designation_id, function (data) {
            original_designation_id = designation_id;
            $('#inputDesignationId').val(data.designation_id);
            $('#inputName').val(data.name);
            $('#btn_confirm').val("update");
            $('#btn_confirm').text("Update");
            $('#modal-title').text('Edit Designation');
            $('#modal-add').modal('show');
        }) 
    });

    //delete designation and remove it from list
    $(document).on('click','.delete-row',function(){
        if(confirm('You are about to delete a designation. Are you sure?')){
            var designation_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "DELETE",
                url: '/deleteDesignation/' + designation_id,
                success: function (data) {
                    $("#designation" + designation_id).remove();
                },
                error: function (data) {
                    console.error('Error:', data.responseJSON);
                }
            });
        }
        
    });
    
    var old_designation_id;
    //Detecting change on edit
    $(document).on('focusin', '#inputDesignationId', function(){
        $(this).data('val', $(this).val());
    }).on('change','#inputDesignationId', function(){
        var current = $(this).val();
        if(state=="update"){
            if($('[id=designation'+original_designation_id+']').length>0 && original_designation_id !=current && $('[id=designation'+current+']').length>0){
                $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already Exists!');
            }else{
                $('#error_msg_id').removeClass('error').addClass('no-error');
            }
        }
        else if(state=="add"){
            if($('[id=designation'+current+']').length>0){
                $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already Exists!');
            }
            else{
                $('#error_msg_id').removeClass('error').addClass('no-error');
            }
        }
    });
    
    //create new product / update existing product
    $("#btn_confirm").click(function (e) {
        if(validate()==true){
            
            var type = "POST"; //for creating new resource
            var designation_id = $('#inputDesignationId').val();
            var url = '/addDesignation'; // by default add designation
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = {
                designation_id      : $('#inputDesignationId').val(),
                name                : $('#inputName').val(),
                company_id          : $('#inputCompanyId').val()
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addDesignation';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateDesignation/' + original_designation_id;
            }
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    var designation = '<tr id="designation' + data.designation_id + '"><td>' + data.designation_id + '</td><td>' + data.name + '</td>';
                    designation += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.designation_id + '"><i class="fa fa-edit"></i></button>';
                    designation += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.designation_id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#designations-list').prepend(designation);
                    }else{ //if user updated an existing record
                        $("#designation" + original_designation_id).replaceWith( designation );
                    }
                    $('#form_addDesignation').trigger("reset");
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
        if($('#inputDesignationId').val()==''){
            validated = false;
            $('#error_msg_id').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var current =$('#inputDesignationId').val();
            if(state=="update"){
                if($('[id=designation'+original_designation_id+']').length>0 && original_designation_id !=current && $('[id=designation'+current+']').length>0){
                    $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already Exists!');
                    validated = false;
                }else{
                    $('#error_msg_id').removeClass('error').addClass('no-error');
                }
            }
            else if(state=="add"){
                if($('[id=designation'+current+']').length>0){
                    $('#error_msg_id').removeClass('no-error').addClass('error').text('ID already Exists!');
                    validated = false;
                }
                else{
                    $('#error_msg_id').removeClass('error').addClass('no-error');
                }
            }
        }
        if($('#inputName').val()==''){
            validated = false;
            $('#error_name').removeClass('no-error').addClass('error');
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