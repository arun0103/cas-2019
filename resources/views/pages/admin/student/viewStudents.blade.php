@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

    <div class="loading">Loading&#8230;</div>
    @if (session('failMessage_duplicateEmail'))
        <div class="alert alert-error alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-minus-circle"></i> Failed!</h4>
            {{ session('failMessage_duplicateEmail') }}
        </div>
    @endif
    <div>
        <input type="hidden" id="inputInstitutionId" disabled value="{{Session::get('company_id')}}">
    </div>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Students
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="studentsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Card #</th>
                    <th>Current Address</th>
                    <th>Guardian Name</th>
                    <th>Relation</th>
                    <th>Grade - Section</th>
                    <th>Contact</th>
                    <th>SMS Enabled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="students-list" name="students-list">
                @foreach($students as $student)
                    <tr id="student{{$student->id}}">
                        <td>{{$student->student_id}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{$student->card_number}}</td>
                        <td>
                            @if($student->temporary_address != null)
                                {{$student->temporary_address}}
                            @else
                                {{$student->permanent_address}}
                            @endif
                        </td>
                        <td>
                            @if($student->guardian_name!= null)
                                {{$student->guardian_name}}
                            @endif
                        </td>
                        <td>
                            @if($student->guardian_relation!=null)
                                {{$student->guardian_relation}}
                            @endif
                        </td>
                        <td>
                            {{$student->grade->name}}
                            @if($student->section !=null)
                                 - {{$student->section->name}}
                            @endif
                        </td>
                        <td>
                            @if($student->contact_1_number!=null)
                                {{$student->contact_1_number}}
                            @endif
                            @if($student->contact_2_number!=null)
                                , {{$student->contact_2_number}}
                            @endif
                        </td>
                        <td>
                            @if($student->sms_option == "N" || $student->sms_option == null)
                                None
                            @elseif($student->sms_option == "1")
                                Yes [1]
                            @elseif($student->sms_option == "2")
                                Yes [2]
                            @elseif($student->sms_option == "B")
                                Yes [Both]
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning open_modal" value="{{$student->id}}"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger delete-row" value="{{$student->id}}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Card #</th>
                    <th>Current Address</th>
                    <th>Guardian Name</th>
                    <th>Relation</th>
                    <th>Grade - Section</th>
                    <th>Contact</th>
                    <th>SMS Enabled</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="modal-add">
        <form id="form_addStudent" class="form-horizontal" method="patch" action="/addStudent" autocomplete="off">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="inputStudentId" class="control-label">Student ID <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputStudentId" placeholder="Student ID" name="student_id">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="inputStudentCardNumber" class="control-label">Card Number <span class="required">*</span></label>
                                    <input type="number" class="form-control" id="inputStudentCardNumber" placeholder="Card Number" name="student_CardNumber">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Student's Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Full name of student" name="student_name">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="datepicker_DOB" class="control-label">D.O.B <span class="required">*</span></label>
                                    <div class="input-group date">
                                        <div class="input-group-addon left-addon">
                                            <input type="text" class="form-control pull-right" id="datepicker_DOB" autocomplete="off">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Gender <span class="required">*</span></label>
                                    <div>
                                        <label for="radio_male" style="font-weight:normal">Male
                                            <input type="radio" id="radio_male" name="gender" value="1" class="flat-red">
                                        </label>
                                        <label for="radio_female" style="font-weight:normal;float:right">Female
                                            <input type="radio" id="radio_female" name="gender" value="0" class="flat-red">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="select_shift" class="control-label">Shift <span class="required">*</span></label>
                                    <select id="select_shift" class="form-control select2 percent100" data-placeholder="Select Shift" name="selectedShift" required>
                                        <option></option>
                                        @foreach($shifts as $shift)
                                            <option value="{{$shift->shift_id}}">{{$shift->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="select_grade" class="control-label">Grade <span class="required">*</span></label>
                                    <select id="select_grade" class="form-control select2 percent100" data-placeholder="Select Grade" name="selectedGrade" onchange="populateSections(this.value)" required>
                                        <option></option>
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->grade_id}}">{{$grade->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="select_section" class="control-label">Section <span class="required">*</span></label>
                                    <select id="select_section" class="form-control select2 percent100" data-placeholder="Select Section" name="selectedSection" >
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="textarea_permanent_address" class="control-label">Permanent Address <span class="required">*</span></label>
                                    <textarea id="textarea_permanent_address" class="form-control" placeholder="Provide complete address"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="textarea_temporary_address" class="control-label">Temporary Address</label>
                                    <textarea id="textarea_temporary_address" class="form-control" placeholder="Provide complete address"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputFatherName" class="control-label">Father's Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputFatherName" placeholder="Father's Name" name="student_father_name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputMotherName" class="control-label">Mother's Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="inputMotherName" placeholder="Mother's Name" name="student_mother_name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputLocalGuardianName" class="control-label">Local Guardian's Name</label>
                                    <input type="text" class="form-control" id="inputLocalGuardianName" placeholder=" Guardian's Name" name="student_local_guardian_name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputGuardianRelation" class="control-label">Relation</label>
                                    <input type="text" class="form-control" id="inputGuardianRelation" placeholder="Provide Relation with guardian" name="student_guardian_relation">
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_contactPersonName_1">Contact Person Name</label>
                                <input type="text" class="form-control" id="input_contactPersonName_1" placeholder="Name of contact person" name="contact_1_name">
                            </div>
                            <div class="col-md-5">
                            <label for="input_contactPersonNumber_1">Mobile Number</label>
                                <input type="text" class="form-control" id="input_contactPersonNumber_1" placeholder="Mobile Number with country code eg.+9779841000000" name="contact_1_number">
                            </div>
                            <div class="col-md-3">
                                <label>SMS Punches</label><br>
                                <input type="checkbox" id="checkbox_enable_sms_on_1"> <label for="checkbox_enable_sms_on_1">Enable </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_contactPersonName_2">Contact Person Name</label>
                                <input type="text" class="form-control" id="input_contactPersonName_2" placeholder="Name of contact person" name="contact_2_name">
                            </div>
                            <div class="col-md-5">
                            <label for="input_contactPersonNumber_2">Mobile Number</label>
                                <input type="text" class="form-control" id="input_contactPersonNumber_2" placeholder="Mobile Number with country code eg.+9779841000000" name="contact_2_number">
                            </div>
                            <div class="col-md-3">
                                <label>SMS Punches</label><br>
                                <input type="checkbox" id="checkbox_enable_sms_on_2"> <label for="checkbox_enable_sms_on_2">Enable </label>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" class="form-control" >
                            </div> 
                        </div>
                    </div>
                   

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_confirm" value="Add">Add</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->"
        </form>
    </div>
    <!-- /.modal -->

@endsection

@section('footer')
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#studentsTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                'scrollX'     : true
            });
            $('.date').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 0,
                autoclose: true,
                todayHighlight: false,
                orientation: "auto"
            });
            $('.select2').select2();
            
            $('.loading').hide();
        });

        //Opening Add Modal
        $('#btn_add').click(function(){
            state="add";

            $('#error_msg_id').removeClass('error').addClass('no-error');
            $('#form_addStudent').trigger("reset");
            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Add Student');
            $('#modal-add').modal('show');    
        });
        function populateSections(grade){
            $('#select_section').empty().trigger('change');
            $.get("/sectionsOfGrade/"+grade, function(data){
                console.log(data);
                $("#select_section").empty();
                $('#select_section').append('<option></option>');
                //alert(data.length);
                for(var i=0;i<data.length;i++){
                    var newSection = $('<option value="'+data[i].section_id+'">'+data[i].name+'</option>');
                    $('#select_section').append(newSection).trigger('change');
                }
            });
        }
        var original_student_id;
        var state;
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var student_id = $(this).val();
            console.log(student_id);
            $.get('/getStudentById/' + student_id, function (data) {
                //success data
                original_student_id = student_id;
                //console.log(original_student_id);
                console.log(data);
                $('#inputStudentId').val(data.student_id);
                $('#inputName').val(data.name);
                $('#select_grade').val(data.grade_id).trigger('change');
                $('#select_shift').val(data.shift_id).trigger('change');
                $('#select_section').val(data.section_id).change();
                $('#inputStudentCardNumber').val(data.card_number);
                $('#datepicker_DOB').val(data.dob);

                if(data.gender==1)
                    $('#radio_male').prop("checked", true);
                else
                    $('#radio_female').prop("checked", true);

                $('#textarea_permanent_address').val(data.permanent_address);
                $('#textarea_temporary_address').val(data.temporary_address);
                $('#inputEmail').val(data.email);
                $('#inputFatherName').val(data.father_name);
                $('#inputMotherName').val(data.mother_name);
                $('#inputLocalGuardianName').val(data.guardian_name);
                $('#inputGuardianRelation').val(data.guardian_relation);
                $('#input_contactPersonName_1').val(data.contact_1_name);
                $('#input_contactPersonName_2').val(data.contact_2_name);
                $('#input_contactPersonNumber_1').val(data.contact_1_number);
                $('#input_contactPersonNumber_2').val(data.contact_2_number);
                switch(data.sms_option){
                    case "1":   $('#checkbox_enable_sms_on_1').prop("checked",true);break;
                    case "2":   $('#checkbox_enable_sms_on_2').prop("checked",true);break;
                    case "B":   $('#checkbox_enable_sms_on_1').prop("checked",true);
                                $('#checkbox_enable_sms_on_2').prop("checked",true);break;
                }
                
                $('#btn_confirm').val("update");
                $('#btn_confirm').text("Update");
                $('#modal-title').text('Edit Student');
                $('#modal-add').modal('show');
            }) 
        });

        //delete student and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete a Student. Are you sure?')){
                var student_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteStudent/' + student_id,
                    success: function (data) {
                        $("#student" + student_id).remove().trigger('change');
                    },
                    error: function (data) {
                        console.error('Error:', data.responseJSON);
                    }
                });
            }
        });
        //create new student / update existing student
        $("#btn_confirm").click(function (e) {
            var type = "POST"; //for creating new resource
            var student_id = $('#inputStudentId').val();
            var url = '/addStudent'; // by default add grade
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                institution_id      : $('#inputInstitutionId').val(),
                student_id          : $('#inputStudentId').val(),
                shift_id            : $('#select_shift').val(),
                grade_id            : $('#select_grade').val(),
                section_id          : $('#select_section').val(),
                name                : $('#inputName').val(), 
                card_number         : $('#inputStudentCardNumber').val(),
                dob                 : $('#datepicker_DOB').val(),
                gender              : $('#radio_male').prop("checked")==true?1:0,
                permanent_address   : $('#textarea_permanent_address').val(),
                temporary_address   : $('#textarea_temporary_address').val(),
                email               : $('#inputEmail').val(),
                father_name         : $('#inputFatherName').val(),
                mother_name         : $('#inputMotherName').val(),
                guardian_name       : $('#inputLocalGuardianName').val(),
                guardian_relation   : $('#inputGuardianRelation').val(),
                contact_1_name      : $('#input_contactPersonName_1').val(),
                contact_2_name      : $('#input_contactPersonName_2').val(),
                contact_1_number    : $('#input_contactPersonNumber_1').val(),
                contact_2_number    : $('#input_contactPersonNumber_2').val(),
                sms_option          : $('#checkbox_enable_sms_on_1').prop("checked")==true && $('#checkbox_enable_sms_on_2').prop("checked")==true ? "B" : $('#checkbox_enable_sms_on_1').prop("checked")==true && $('#checkbox_enable_sms_on_2').prop("checked")==false ? "1": $('#checkbox_enable_sms_on_1').prop("checked")==false && $('#checkbox_enable_sms_on_2').prop("checked")==true ? "2" : "N",
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addStudent';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateStudent/' + original_student_id;
            }
            console.log(formData);
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    console.log('Success');
                    var current_address = "";
                    if(data.temporary_address != null){
                        current_address = data.temporary_address;
                    }else{
                        current_address = data.permanent_address;
                    }
                    var student_grade_section = "";
                    if(data.grade != null){
                        console.log("grade not null");
                        student_grade_section = data.grade['name'];
                    }
                    if(data.section != null){
                        console.log("section not null");
                        student_grade_section += " - " + data.section['name'];
                    }
                    var guardian_name = data.guardian_name!=null ? data.guardian_name : '';
                    var guardian_relation = data.guardian_relation != null ? data.guardian_relation : '';
                    var contact_numbers = data.contact_2_number!=null && data.contact_1_number !=null ? data.contact_1_number +', ' + data.contact_2_number : data.contact_1_number!=null ? data.contact_1_number : ''; 
                    var sms_options = "";
                    switch(data.sms_option){
                        case "1": sms_options = "Yes [1]";break;
                        case "2": sms_options = "Yes [2]";break;
                        case "B": sms_options = "Yes [Both]";break;
                        default : sms_options = "None";
                    }
                    var student = '<tr id="student'+data.id +'"><td>'+ data.student_id + '</td><td>'
                                        + data.name + '</td><td>' 
                                        + data.card_number + '</td><td>' 
                                        + current_address + '</td><td>'
                                        + guardian_name + '</td><td>' 
                                        + guardian_relation + '</td><td>' 
                                        + student_grade_section + '</td><td>'
                                        + contact_numbers+ '</td><td>' 
                                        + sms_options + '</td>' ;
                    student += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"></i></button>';
                    student += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"></i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        console.log("Added");
                        console.log(data);
                        $('#students-list').prepend(student);
                    }else{ //if user updated an existing record
                        $("#student" + original_student_id).replaceWith( student );
                    }
                    $('#form_addStudent').trigger("reset");
                    $('#modal-add').modal('hide');
                },
                error: function (data) {
                    alert('Error: '+JSON.stringify(data['responseJSON']));
                    console.log('Error:', data);
                }
            });
        });
        
    </script>
@endsection