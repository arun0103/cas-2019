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
            <h3 class="box-title">List Of Branches
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <div class="box-body">
            <table id="branchTable" class="table table-bordered table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Branch ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Website</th>
                    <th>Contact</th>
                    <th>VAT Number</th>
                    <th>PAN Number</th>
                    <th>Registration Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="branches-list" name="branches-list">
                @foreach($branches as $branch)
                <tr id="branch{{$branch->branch_id}}">
                    <td>{{$branch->branch_id}}</td>
                    <td>{{$branch->name}}</td>
                    <td>{{$branch->street_address_1}}, {{$branch->street_address_2}}, {{$branch->city}}, {{$branch->state}}, {{$branch->country}} - {{$branch->postal_code}}</td>
                    <td>{{$branch->website}}</td>
                    <td>{{$branch->contact}}</td>
                    <td>{{$branch->VAT_number}}</td>
                    <td>{{$branch->PAN_number}}</td>
                    <td>{{$branch->registration_number}}</td>
                    <td>
                        <button class="btn btn-warning open_modal" value="{{$branch->branch_id}}"><i class="fa fa-edit"> </i></button>
                        <button class="btn btn-danger delete-branch" value="{{$branch->branch_id}}"><i class="fa fa-trash"> </i></button>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Website</th>
                    <th>Contact</th>
                    <th>VAT Number</th>
                    <th>PAN Number</th>
                    <th>Registration Number</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-add">
        <form id="form_addBranch" class="form-horizontal" autocomplete="nope"> 
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Branch</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row roundPadding20" id="addNewBranch"> 
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="inputBranchId" class="control-label">Branch ID <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputBranchId" placeholder="Branch ID" name="branch_id" autocomplete="no">
                                            <span id="error_branch_id" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Name <span class="">*</span></label>
                                            <input type="text" class="form-control" id="inputName" placeholder="Name" name="branch_name" autocomplete="no" >
                                            <span id="error_name" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="inputWebsite" class="control-label">Website</label>
                                            <input type="text" class="form-control" id="inputWebsite" placeholder="Website" name="website" autocomplete="no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputContactNumber" class="control-label">Contact Number <span class="required">*</span></label>
                                            <input type="number" class="form-control" id="inputContactNumber" placeholder="Contact Number" name="contact" autocomplete="no" min="1" >
                                            <span id="error_contact" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputCountry" class="control-label">Country <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputCountry" placeholder="Country" name="country" autocomplete="country" >
                                            <span id="error_country" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputState" class="control-label">State <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputState" placeholder="State" name="state" autocomplete="state">
                                            <span id="error_state" class="no-error">Required!</span>
                                        </div>   
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputCity" class="control-label">City <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputCity" placeholder="City" name="city" autocomplete="city">
                                            <span id="error_city" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputStreet_address_1" class="control-label">Street address 1 <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputStreet_address_1" placeholder="Street address 1" name="street_address_1" autocomplete="no">
                                            <span id="error_street_1" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputStreet_address_2" class="control-label">Street Address 2 <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputStreet_address_2" placeholder="Street Address 2" name="street_address_2" autocomplete="no">
                                            <span id="error_street_2" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputPostalCode" class="control-label">Postal Code <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="inputPostalCode" placeholder="Postal Code" name="postalCode" autocomplete="no">
                                            <span id="error_postal" class="no-error">Required!</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputRegistrationNumber" class="control-label">Registration Number</label>
                                            <input type="text" class="form-control" id="inputRegistrationNumber" placeholder="Registration Number" name="registration_number" autocomplete="no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputVATNumber" class="control-label">VAT Number</label>
                                            <input type="text" class="form-control" id="inputVATNumber" placeholder="VAT Number" name="VAT_number" autocomplete="no">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputPANNumber" class="control-label">PAN Number</label>
                                            <input type="text" class="form-control" id="inputPANNumber" placeholder="PAN Number" name="PAN_number" autocomplete="no">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLatitude" class="control-label">Latitude</label>
                                            <input type="text" class="form-control" id="inputLatitude" placeholder="Latitude" name="latitude" autocomplete="no">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputLongitude" class="control-label">Longitude</label>
                                            <input type="text" class="form-control" id="inputLongitude" placeholder="Longitude" name="longitude" autocomplete="no">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Admin Email</label>
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Admin Email" name="email" autocomplete="no">
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
    var state;
    var original_branch_id;
    $(document).ready(function() {
        $('#branchTable').DataTable({
            'paging'        : true,
            'lengthChange'  : true,
            'searching'     : true,
            'ordering'      : true,
            'info'          : true,
            'autoWidth'     : true,
            "scrollX"       : true
        });
        $('.loading').hide();
    });
    $('#btn_add').click(function(){
        state="add";
        $('.error').removeClass('error').addClass('no-error');
        $('#inputBranchId').prop('disabled',false);
        $('#btn_confirm').val("add");
        $('#btn_confirm').text("Add");
        $('#modal-title').text('Add Branch');
        $('#form_addBranch').trigger("reset");
        $('#modal-add').modal('show');
    });
    $(document).on('click', '.open_modal', function(){
        state="update";
        $(".error").removeClass("error").addClass('no-error');
        var branch_id = $(this).val();
        $.get('/getBranchById/' + branch_id, function (data) {
            original_branch_id = branch_id;
            $('#inputBranchId').val(data.branch_id).prop('disabled',true);
            $('#inputName').val(data.name);
            $('#inputWebsite').val(data.website);
            $('#inputContactNumber').val(data.contact);
            $('#inputCountry').val(data.country);
            $('#inputState').val(data.state);
            $('#inputCity').val(data.city);
            $('#inputStreet_address_1').val(data.street_address_1);
            $('#inputStreet_address_2').val(data.street_address_2);
            $('#inputPostalCode').val(data.postal_code);
            $('#inputVATNumber').val(data.VAT_number);
            $('#inputPANNumber').val(data.PAN_number);
            $('#inputRegistrationNumber').val(data.registration_number);
            $('#inputLatitude').val(data.lat);
            $('#inputLongitude').val(data.lng);
            $('#inputEmail').val(data.email); 
            $('#btn_confirm').val("update");
            $('#btn_confirm').text("Update");
            $('#modal-title').text('Edit Branch');
            $('#modal-add').modal('show');
        }) 
    });
    $(document).on('click','.delete-branch',function(){
        if(confirm('You are about to delete a department. Are you sure?')){
            var branch_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "DELETE",
                url: '/deleteBranch/' + branch_id,
                success: function (data) {
                    $("#branch" + branch_id).remove();
                },
                error: function (data) {
                    console.error('Error:', data);
                    alert("Something went wrong! \nPlease Try again later!");
                }
            });
        }
    });
    
    var old_branch_id;
    
    $(document).on('focusin', '#inputBranchId', function(){
        $(this).data('val', $(this).val());
    }).on('change','#inputBranchId', function(){
        var current = $(this).val();
        if(state=="update"){
            if($('[id=branch'+original_branch_id+']').length>0 && original_branch_id !=current && $('[id=branch'+current+']').length>0){
                $('#error_branch_id').removeClass('no-error').addClass('error');
            }
            else{
                $('#error_branch_id').removeClass('error').addClass('no-error');
            }
        }else if(state=="add"){
            if($('[id=branch'+current+']').length>0){
                $('#error_branch_id').removeClass('no-error').addClass('error');
            }
            else{
                $('#error_branch_id').removeClass('error').addClass('no-error');
            }
        }
        
    });
    $("#btn_confirm").click(function (e) {
        if(validate()){
            var type = "POST"; //for creating new resource
            var branch_id = $('#inputBranchId').val();
            var url = '/addBranch'; // by default add department
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                branch_id           : $('#inputBranchId').val(),
                name                : $('#inputName').val(),
                company_id          : $('#inputCompanyId').val(),
                country             : $('#inputCountry').val(),
                state               : $('#inputState').val(),
                city                : $('#inputCity').val(),
                street_address_1    : $('#inputStreet_address_1').val(),
                street_address_2    : $('#inputStreet_address_2').val(),
                postal_code         : $('#inputPostalCode').val(),
                website             : $('#inputWebsite').val(),
                contact             : $('#inputContactNumber').val(),
                VAT_number          : $('#inputVATNumber').val(),
                PAN_number          : $('#inputPANNumber').val(),
                registration_number : $('#inputRegistrationNumber').val(),
                lat                 : $('#inputLatitude').val(),
                lng                 : $('#inputLongitude').val(),
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addBranch';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateBranch/' + original_branch_id;
            }
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    var branch = '<tr id="branch' + data.branch_id + '"><td>' + data.branch_id + '</td><td>'
                            + data.name + '</td><td>'
                            +data.street_address_1+', '+data.street_address_2+', '+data.city+', '+data.state+', '+data.country+' - '+data.postal_code+'</td>'
                            +'<td>'+data.website+'</td>'
                            +'<td>'+data.contact+'</td>'
                            +'<td>'+data.VAT_number+'</td>'
                            +'<td>'+data.PAN_number+'</td>'
                            +'<td>'+data.registration_number+'</td>';
                    branch += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.branch_id + '"><i class="fa fa-edit"> </i></button>';
                    branch += ' <button class="btn btn-danger btn-delete delete-branch" value="' + data.branch_id + '"><i class="fa fa-trash"> </i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#branches-list').prepend(branch);
                    }else{ //if user updated an existing record
                        $("#branch" + original_branch_id).replaceWith( branch );
                    }
                    $('#form_branch').trigger("reset");
                    $('#modal-add').modal('hide');
                },
                error: function (data) {
                    alert("Something went wrong! Please Try Again Later!")
                }
            });
        }
    });
    function validate(){
        var validated = true;
        if($('#inputBranchId').val()==''){
            validated = false;
            $('#error_branch_id').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_branch_id').removeClass('error').addClass('no-error');
            var current = $('#inputBranchId').val();
            if(state=="update"){
                if($('[id=branch'+original_branch_id+']').length>0 && original_branch_id !=current && $('[id=branch'+current+']').length>0){
                    $('#error_branch_id').removeClass('no-error').addClass('error');
                    validated = false;
                }
                else{
                    $('#error_branch_id').removeClass('error').addClass('no-error');
                }
            }else if(state=="add"){
                if($('[id=branch'+current+']').length>0){
                    $('#error_branch_id').removeClass('no-error').addClass('error');
                    validated = false;
                }
                else{
                    $('#error_branch_id').removeClass('error').addClass('no-error');
                }
            }
        }
        if($('#inputName').val()==''){
            validated = false;
            $('#error_name').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_name').removeClass('error').addClass('no-error');
        }
        if($('#inputCountry').val()==''){
            validated = false;
            $('#error_country').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_country').removeClass('error').addClass('no-error');
        }
        if($('#inputState').val()==''){
            validated = false;
            $('#error_state').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_state').removeClass('error').addClass('no-error');
        }
        if($('#inputCity').val()==''){
            validated = false;
            $('#error_city').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_city').removeClass('error').addClass('no-error');
        }
        if($('#inputStreet_address_1').val()==''){
            validated = false;
            $('#error_street_1').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_street_1').removeClass('error').addClass('no-error');
        }
        if($('#inputStreet_address_2').val()==''){
            validated = false;
            $('#error_street_2').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_street_2').removeClass('error').addClass('no-error');
        }
        if($('#inputPostalCode').val()==''){
            validated = false;
            $('#error_postal').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_postal').removeClass('error').addClass('no-error');
        }
        if($('#inputContactNumber').val()==''){
            validated = false;
            $('#error_contact').removeClass('no-error').addClass('error').text('Required!');
        }else{
            $('#error_contact').removeClass('error').addClass('no-error');
        }
        return validated;
    }
    $('#inputName').keyup(function(){
        if($('#inputName').val()!='')
            $('#error_name').removeClass('error').addClass('no-error');
        else
            $('#error_name').removeClass('no-error').addClass('error');
    });
    $('#inputCountry').keyup(function(){
        if($('#inputCountry').val()!='')
            $('#error_country').removeClass('error').addClass('no-error');
        else
            $('#error_country').removeClass('no-error').addClass('error');
    });
    $('#inputState').keyup(function(){
        if($('#inputState').val()!='')
            $('#error_state').removeClass('error').addClass('no-error');
        else
            $('#error_state').removeClass('no-error').addClass('error');
    });
    $('#inputCity').keyup(function(){
        if($('#inputCity').val()!='')
            $('#error_city').removeClass('error').addClass('no-error');
        else
            $('#error_city').removeClass('no-error').addClass('error');
    });
    $('#inputStreet_address_1').keyup(function(){
        if($('#inputStreet_address_1').val()!='')
            $('#error_street_1').removeClass('error').addClass('no-error');
        else
            $('#error_street_1').removeClass('no-error').addClass('error');
    });
    $('#inputStreet_address_2').keyup(function(){
        if($('#inputStreet_address_2').val()!='')
            $('#error_street_2').removeClass('error').addClass('no-error');
        else
            $('#error_street_2').removeClass('no-error').addClass('error');
    });
    $('#inputPostalCode').keyup(function(){
        if($('#inputPostalCode').val()!='')
            $('#error_postal').removeClass('error').addClass('no-error');
        else
            $('#error_postal').removeClass('no-error').addClass('error');
    });
    $('#inputContactNumber').keyup(function(){
        if($('#inputContactNumber').val()!='')
            $('#error_contact').removeClass('error').addClass('no-error');
        else
            $('#error_contact').removeClass('no-error').addClass('error');
    });
</script>
@endsection