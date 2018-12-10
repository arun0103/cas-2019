@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="loading">Loading&#8230;</div>
    @if (session('successMessage'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check-circle"></i> Alert!</h4>
            {{ session('message') }}
        </div>
    @endif
    @if (session('failMessage'))
        <div class="alert alert-error alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-minus-circle"></i> Alert!</h4>
            {{ session('failMessage') }}
        </div>
    @endif
    <div>
        <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">
    </div>
    <div class="box">
    <div class="box-header">
        <h3 class="box-title">List Of Employees
        <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
        </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="employeeTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Father's Name</th>
                <th>Emp.ID</th>
                <th>Card No.</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Branch Name</th>
                <th>Shift Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="employees-list" name="employees-list">
            @foreach($employees as $emp)
                <tr id="employee{{$emp->employee_id}}">
                    <td>{{$emp->name}}</td>
                    <td>{{$emp->father_name}}</td>
                    <td>{{$emp->employee_id}}</td>
                    <td>{{$emp->card_number}}</td>
                    <td>{{$emp->department->name}}</td>
                    <td>{{$emp->designation->name}}</td>
                    <td>{{$emp->branch->name}}</td>
                    <td>{{$emp->first_shift->name}}</td>
                    <td>
                        <button class="btn btn-warning open_modal" value="{{$emp->employee_id}}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger delete-row" value="{{$emp->employee_id}}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Employee Name</th>
                <th>Father's Name</th>
                <th>Emp.ID</th>
                <th>Card No.</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Branch Name</th>
                <th>Shift Name</th>
                <th>Actions</th>
            </tr>
        </tfoot>
        </table>
    </div>
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg" style="width:95% !important;height:95% !important; padding:0;margin:0 auto">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row ml-3" id="addNewEmployee">
                        <div class="nav-tabs-custom" id="tabs">
                            <ul class="nav nav-tabs mr-auto">
                                <li id="tab_1_li" class="nav-item  active"><a id="a_tab_1" class="nav-item nav-link active" data-toggle="tab" href="#tab_1" aria-expanded="true">Personal Info</a></li>
                                <li id="tab_2_li" class="nav-item disabled" ><a id="a_tab_2" class="nav-item nav-link" data-toggle="tab" href="#tab_2" aria-expanded="false">Official Info</a></li>
                                <li id="tab_3_li" class="nav-item disabled" ><a id="a_tab_3" class="nav-item nav-link" data-toggle="tab" href="#tab_3" aria-expanded="false">Bank Info</a></li>
                                <!-- <li class="nav-item pull-right"><a class="nav-link" href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
                            </ul>
                            <div class="tab-content" style="padding:25px">
                                <div class="tab-pane active" id="tab_1">
                                    <form id="form_addEmployee_t1" class="form-horizontal" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputEmployeeId" class="control-label">Employee ID <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="inputEmployeeId" placeholder="Employee ID" name="employee_id" required>
                                                            <span id="error_employee_id" class="no-error">ID already exists!</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputName" class="control-label">Name <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="inputName" placeholder="Name" name="employee_name" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Gender <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_male">Male
                                                                    <input type="radio" id="radio_male" name="gender" value="1" class="flat-red" checked>
                                                                </label>
                                                                <label for="radio_female">Female
                                                                    <input type="radio" id="radio_female" name="gender" value="0" class="flat-red">
                                                                </label>
                                                            </div>
                                                        </div>                                                       
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Employee Photo</label>
                                                        <div class="row" id="image_preview">
                                                            <img id="previewing" src="{{asset('img/avatar.png')}}" alt="your image" style="width:150px;height:150px;border:1px solid black">
                                                        </div>
                                                        <div class="row">
                                                        <!-- <form action="/uploadfile" method="post" enctype="multipart/form-data"> -->
                                                                @csrf
                                                                <div >
                                                                    <input type="file" class="form-control-file" name="fileToUpload" id="inputEmployeePhoto" aria-describedby="fileHelp" >
                                                                    <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                                                                </div>
                                                                
                                                            <!-- </form> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div><label for="datepicker_DOB" class="control-label">D.O.B <span class="required">*</span></label></div>
                                                            <div class="input-group date">
                                                                <div class="input-group-addon left-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    <input type="text" class="form-control pull-right datePicker" id="datepicker_DOB" autocomplete="off" required>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Maritial Status <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_single">Single
                                                                    <input type="radio" id="radio_single" name="maritial_status" value="single" class="flat-red" checked>
                                                                </label>
                                                                <label for="radio_married">Married
                                                                    <input type="radio" id="radio_married" name="maritial_status" value="married" class="flat-red">
                                                                </label>
                                                                <label for="radio_divorced">Divorced
                                                                    <input type="radio" id="radio_divorced" name="maritial_status" value="divorced" class="flat-red">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group notSingle">
                                                            <label for="inputSpouseName" class="control-label">Spouse Name</label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputSpouseName" placeholder="Spouse Name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group notSingle">
                                                            <div><label for="datepicker_anniversary" class="control-label">Anniversary Date</label></div>
                                                            <div>
                                                                <div class="input-group date">
                                                                    <div class="input-group-addon left-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                        <input type="text" class="form-control pull-right datePicker" id="datepicker_anniversary" autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="inputFatherName" class="control-label">Father Name <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="inputFatherName" placeholder="Father's Name" name="father_name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputEducationalQualification" class="control-label">Educational Qualification <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputEducationalQualification" placeholder="Educational Qualification" name="educational_qualification" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputProfessionalQualification" class="control-label">Professional Qualification <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputProfessionalQualification" placeholder="Professional Qualification" name="professional_qualification" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputMobile1" class="control-label">Mobile Number 1 <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputMobile1" placeholder="Mobile Number with country code" name="mobile1" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputMobile2" class="control-label">Mobile Number 2</label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputMobile2" placeholder="Mobile Number with country code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputExperience" class="control-label">Experience <span class="required">*</span></label>
                                                    <div>
                                                        <input type="text" class="form-control" id="inputExperience" placeholder="Experience (in years)" name="experience" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail" class="control-label">Email</label>
                                                    <div>
                                                        <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputCountry" class="control-label">Country <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text"  class="form-control" id="inputCountry" placeholder="Country" name="country" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputState" class="control-label">State <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text"  class="form-control" id="inputState" placeholder="State" name="state" required>
                                                            </div>
                                                        </div>                                                     
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputCity" class="control-label">City <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text"  class="form-control" id="inputCity" placeholder="City" name="city" required>
                                                            </div>
                                                        </div> 
                                                    </div>   
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputStreet_address_1" class="control-label">Street Address 1 <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text"  class="form-control" id="inputStreet_address_1" placeholder="Street Address 1" name="street_address_1" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputStreet_address_2" class="control-label">Street Address 2</label>
                                                            <div>
                                                                <input type="text" class="form-control" id="inputStreet_address_2" placeholder="Street Address 2" name="street_address_2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="inputPostalCode" class="control-label">Postal Code <span class="required">*</span></label>
                                                            <div>
                                                                <input type="number" class="form-control" id="inputPostalCode" placeholder="Postal Code" name="postal_code" min="1" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <hr>
                                                <button type="button" class="btn btn-warning right" id="btn_tab_1_next">Next</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <form id="form_addEmployee_t2" class="form-horizontal" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="inputCardNumber" class="control-label">Card Number <span class="required">*</span></label>
                                                            <input type="number" class="form-control" id="inputCardNumber" placeholder="Employee Card Number" name="employee_card_number" min="1" required>
                                                            <span id="error_cardNumber" class="no-error">Card Number is Duplicate!</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_department" class="control-label">Department <span class="required">*</span></label>
                                                            <select id="select_department" class="form-control select2" data-placeholder="Select a Department" name="department" required>
                                                                <option></option>
                                                                @foreach($departments as $department)
                                                                <option value="{{$department->department_id}}">{{$department->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_category" class="control-label">Category <span class="required">*</span></label>
                                                            <select id="select_category" class="form-control select2" data-placeholder="Select a Category" name="category" required>
                                                                <option></option>
                                                                @foreach($categories as $category)
                                                                <option value="{{$category->category_id}}">{{$category->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_branch" class="control-label">Branch <span class="required">*</span></label>
                                                            <select id="select_branch" class="form-control select2" data-placeholder="Select a Branch" name="branch" required>
                                                                <option></option>
                                                                @foreach($branches as $branch)
                                                                    <option value="{{$branch->branch_id}}">{{$branch->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_designation" class="control-label">Designation <span class="required">*</span></label>
                                                            <select id="select_designation" class="form-control select2" data-placeholder="Select a Designation" name="designation" required>
                                                                <option></option>
                                                                @foreach($designations as $designation)
                                                                <option value="{{$designation->designation_id}}">{{$designation->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Type <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_temporary">Temporary
                                                                    <input type="radio" id="radio_temporary" name="type" value="0" class="flat-red" checked>
                                                                </label>
                                                                <label for="radio_provasion">Provasion
                                                                    <input type="radio" id="radio_provasion" name="type" value="1" class="flat-red">
                                                                </label>
                                                                <label for="radio_permanent">Permanent
                                                                    <input type="radio" id="radio_permanent" name="type" value="2" class="flat-red">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="select_weekOffDay" class="control-label">Week Off on <span class="required">*</span></label>
                                                            <select id="select_weekOffDay" class="form-control select2" data-placeholder="Select a Day" name="week_off_day" required>
                                                                <option></option>
                                                                <option value="7">Sunday</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="select_additionalOffDay" class="control-label">Additional Off on </label>
                                                            <select id="select_additionalOffDay" class="form-control select2" data-placeholder="Select a Day">
                                                                <option></option>
                                                                <option value="0">Sunday</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="select_additionalOffWeek" class="control-label">Additional Off Week</label>
                                                            <select id="select_additionalOffWeek" class="form-control select2" multiple data-placeholder="Select Week(s)">
                                                                <option></option>
                                                                <option value="1">1st Week</option>
                                                                <option value="2">2nd Week</option>
                                                                <option value="3">3rd Week</option>
                                                                <option value="4">4th Week</option>
                                                                <option value="5">5th Week</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_shift_1" class="control-label">Shift 1 <span class="required">*</span></label>
                                                            <select id="select_shift_1" class="form-control select2" data-placeholder="Select a Shift" name="shift_1" required>
                                                                <option></option>
                                                                @foreach($shifts as $shift)
                                                                <option value="{{$shift->shift_id}}">
                                                                <div>
                                                                    <h4>{{$shift->name}}</h4><br/>
                                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_shift_2" class="control-label">Shift 2</label>
                                                            <select id="select_shift_2" class="form-control select2" data-placeholder="Select a Shift">
                                                                <option></option>
                                                                @foreach($shifts as $shift)
                                                                <option value="{{$shift->shift_id}}">
                                                                <div>
                                                                    <h4>{{$shift->name}}</h4><br/>
                                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_shift_3" class="control-label">Shift 3</label>
                                                            <select id="select_shift_3" class="form-control select2" data-placeholder="Select a Shift">
                                                                <option></option>
                                                                @foreach($shifts as $shift)
                                                                <option value="{{$shift->shift_id}}">
                                                                <div>
                                                                    <h4>{{$shift->name}}</h4><br/>
                                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_shift_4" class="control-label">Shift 4</label>
                                                            <select id="select_shift_4" class="form-control select2" data-placeholder="Select a Shift">
                                                                <option></option>
                                                                @foreach($shifts as $shift)
                                                                <option value="{{$shift->shift_id}}">
                                                                <div>
                                                                    <h4>{{$shift->name}}</h4><br/>
                                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Auto Shift Select<span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_autoSelectShift_yes">Yes
                                                                    <input type="radio" id="radio_autoSelectShift_yes" name="auto_select_shift" value="1" class="flat-red">
                                                                </label>
                                                                <label for="radio_autoSelectShift_no">No
                                                                    <input type="radio" id="radio_autoSelectShift_no" name="auto_select_shift" value="0" class="flat-red" checked>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Change By Week <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_changeByWeek_yes">Yes
                                                                    <input type="radio" id="radio_changeByWeek_yes" name="change_by_week" value="1" class="flat-red">
                                                                </label>
                                                                <label for="radio_changeByWeek_no">No
                                                                    <input type="radio" id="radio_changeByWeek_no" name="change_by_week" value="0" class="flat-red" checked>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Change After Day</label>
                                                            <div>
                                                                <label for="radio_changeAfterDay_yes">Yes
                                                                    <input type="radio" id="radio_changeAfterDay_yes" name="change_after_day" value="1" class="flat-red">
                                                                </label>
                                                                <label for="radio_changeAfterDay_no">No
                                                                    <input type="radio" id="radio_changeAfterDay_no" name="change_after_day" value="0" class="flat-red" checked>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="select_changed_on_day" class="control-label">Change On Day</label>
                                                            <select id="select_changed_on_day" class="form-control select2" data-placeholder="Select a Day">
                                                                <option></option>
                                                                <option value="0">Sunday</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_half_day_shift" class="control-label">Half Day Shift</label>
                                                            <select id="select_half_day_shift" class="form-control select2" data-placeholder="Select a Shift">
                                                                <option></option>
                                                                @foreach($shifts as $shift)
                                                                <option value="{{$shift->shift_id}}">
                                                                <div>
                                                                    <h4>{{$shift->name}}</h4><br/>
                                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_half_day_on" class="control-label">Half Day On</label>
                                                            <select id="select_half_day_on" class="form-control select2" data-placeholder="Select a Day">
                                                                <option></option>
                                                                <option value="0">Sunday</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Comp Off Applicable <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_comp_off_yes">Yes
                                                                    <input type="radio" id="radio_comp_off_yes" name="comp_off_applicable" value="1" class="flat-red" checked>
                                                                </label>
                                                                <label for="radio_comp_off_no">No
                                                                    <input type="radio" id="radio_comp_off_no" name="comp_off_applicable" value="0" class="flat-red">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Overtime applicable <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_overtime_yes">Yes
                                                                    <input type="radio" id="radio_overtime_yes" name="overtime_applicable" value="1" class="flat-red" checked>
                                                                </label>
                                                                <label for="radio_overtime_no">No
                                                                    <input type="radio" id="radio_overtime_no" name="overtime_applicable" value="0" class="flat-red">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="select_ReportingOfficer1" class="control-label">Reporting Officer 1</label>
                                                            <select id="select_ReportingOfficer1" class="form-control select2 reporting_officers"  data-placeholder="Select reporting officer" onchange="changed_reporting_officer1(this.value)">
                                                                <option></option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{$employee->employee_id}}">
                                                                        <div>
                                                                            <h4>{{$employee->name}}</h4><br/>
                                                                            <span>[{{$employee->designation->name}}]</span>
                                                                        </div>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                        <label for="select_ReportingOfficer2" class="control-label">Reporting Officer 2</label>
                                                            <select id="select_ReportingOfficer2" class="form-control select2 reporting_officers" data-placeholder="Select reporting officer" onchange="changed_reporting_officer2(this.value)">
                                                                <option></option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{$employee->employee_id}}">
                                                                        <div>
                                                                            <h4>{{$employee->name}}</h4><br/>
                                                                            <span>[{{$employee->designation->name}}]</span>
                                                                        </div>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div><label for="datepicker_joiningDate" class="control-label">Joining Date <span class="required">*</span></label></div>
                                                            <div class="input-group date">
                                                                <div class="input-group-addon left-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    <input type="text" class="form-control pull-right datePicker" id="datepicker_joiningDate" autocomplete="off" name="joining_date" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div><label for="datepicker_leavingDate" class="control-label">Leaving Date</label></div>
                                                            <div class="input-group date">
                                                                <div class="input-group-addon left-addon">
                                                                    <input type="text" class="form-control pull-right" id="datepicker_LeavingDate" autocomplete="off">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputReferredBy">Referred By</label>
                                                    <input type="text" class="form-control" id="inputReferredBy" name="referredBy" placeholder="Name of the referrer">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="button" class="btn btn-warning" id="btn_tab_2_back">Back</button>
                                        <button type="button" class="btn btn-warning right" id="btn_tab_2_next">Next</button>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_3">
                                    <form id="form_addEmployee_t3" class="form-horizontal" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="input_ESI_number" class="control-label">ESI Number <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_ESI_number" placeholder="ESI Number" name="esi" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="input_PF_number" class="control-label">PF Number <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_PF_number" placeholder="PF Number" name="pf" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="input_PAN_number" class="control-label">PAN Number <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_PAN_number" placeholder="PAN Number" name="pan" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="input_UAN_number" class="control-label">UAN Number <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_UAN_number" placeholder="UAN Number" name="uan" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Wage Type <span class="required">*</span></label>
                                                            <div>
                                                                <label for="radio_daily">Daily
                                                                    <input type="radio" id="radio_daily" name="wage_type" value="0" class="flat-red">
                                                                </label>
                                                                <label for="radio_monthly">Monthly
                                                                    <input type="radio" id="radio_monthly" name="wage_type" value="1" class="flat-red" checked>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="input_bank_name" class="control-label">Bank Name <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_bank_name" placeholder="Bank Name" name="bank_name" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="input_bank_branch" class="control-label">Branch <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_bank_branch" placeholder="Branch" name="bank_branch" required>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="input_IFSC_code" class="control-label">IFSC Code <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_IFSC_code" placeholder="IFSC Code" name="ifsc_code" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="input_account_number" class="control-label">Account Number <span class="required">*</span></label>
                                                            <div>
                                                                <input type="text" class="form-control" id="input_account_number" placeholder="Account Number" name="account_number" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2">
                                                        <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                        </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div> 
                                        <hr>
                                        <button type="button" class="btn btn-warning" id="btn_tab_3_back">Back</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_confirm" value="Add">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('js/plugins/jquery/jquery.validate.min.js')}}"></script>
    <script>
        var employee_table;
        var employee_id;
        var validated = true;
        $(document).ready(function () {
            employee_table = $('#employeeTable').DataTable({
                'paging'        : true,
                'lengthChange'  : true,
                'searching'     : true,
                'ordering'      : true,
                'info'          : true,
                'autoWidth'     : true,
                'scrollX'       : true
            });
            
            $('#tabs').tabs();
            
            $('.date').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 0,
                //calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                //rtl: true,
                orientation: "auto"
            });
            //Initialize Select2 Elements
            $('.select2').select2({
                allowClear: true
            });
            $('.loading').hide();
            // Function to preview image after validation
            $(function() {
                $("#inputEmployeePhoto").change(function() {
                    //$("#message").empty(); // To remove the previous error message
                    var file = this.files[0];
                    var imagefile = file.type;
                    var match= ["image/jpeg","image/png","image/jpg"];
                    if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
                    {
                        $('#previewing').attr('src','noimage.png');
                        //$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                        return false;
                    }
                    else
                    {
                        var reader = new FileReader();
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
            function imageIsLoaded(e) {
                $("#inputEmployeePhoto").css("color","green");
                //$('#image_preview').css("display", "block");
                $('#previewing').attr('src', e.target.result);
                $('#previewing').attr('width', '250px');
                $('#previewing').attr('height', '230px');
            };

        });
        $('#btn_tab_1_next').click(function(e){
            if($('#form_addEmployee_t1').valid() && validated == true){
                $( "#tabs" ).tabs({
                    active: 1
                });
                $("label.error").hide();
                $(".error").removeClass("error");

                $('#modal-add').animate({ scrollTop: 0 }, 'slow');
                $('#tab_2_li').removeClass('disabled');
            }else{
                alert('Some values are missing! \nPlease Check!');
            }
        });
        $('#btn_tab_2_next').click(function(e){
            if($('#form_addEmployee_t2').valid()){
                $( "#tabs" ).tabs({
                    active: 2
                });
                $('#modal-add').animate({ scrollTop: 0 }, 'slow');
            }else{
                alert('Some values are missing! \nPlease Check!');
            }
        });
        $('#btn_tab_2_back').click(function(e){
            $( "#tabs" ).tabs({
                active: 0
            });
                $('#modal-add').animate({ scrollTop: 0 }, 'slow');
        });
        $('#btn_tab_3_back').click(function(e){
            $( "#tabs" ).tabs({
                active: 1
            });
            $('#modal-add').animate({ scrollTop: 0 }, 'slow');
            
        });
        $('#btn_add').click(function(){
            state="add";
            $("label.error").hide();
            $(".error").removeClass("error");
            $( "#tabs" ).tabs( "destroy" );
            $( "#tabs" ).tabs({
                active: 0
            });
            
            $('#a_tab_1').addClass('active');
            $('#a_tab_2').removeClass('active');
            $('#a_tab_3').removeClass('active');

            $('#error_msg_id').removeClass('error').addClass('no-error');

            $('#form_addEmployee_t1').trigger("reset");
            $('#form_addEmployee_t2').trigger("reset");
            $('#form_addEmployee_t3').trigger("reset");
            $('#select_department').val([]).change();
            $('#select_category').val([]).change();
            $('#select_branch').val([]).change();
            $('#select_designation').val([]).change();
            $('#select_weekOffDay').val([]).change();
            $('#select_additionalOffDay').val([]).change();
            $('#select_additionalOffWeek').val([]).change();
            $('#select_shift_1').val([]).change();
            $('#select_shift_2').val([]).change();
            $('#select_shift_3').val([]).change();
            $('#select_shift_4').val([]).change();

            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Add Employee');
            $('#modal-add').modal('show');   
            
        });
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            employee_id = $(this).val();
            $("label.error").hide();
            $(".error").removeClass("error");

            $( "#tabs" ).tabs( "destroy" );
            $( "#tabs" ).tabs({
                active: 0
            });

            $('#a_tab_2').removeClass('active');
            $('#a_tab_3').removeClass('active');

            $.each($('#select_ReportingOfficer1 option[value]'),function(key,val){
                if(employee_id == val.value){
                    $('.reporting_officers option[value='+val.value+']').prop('disabled',true);
                }
                else
                    $('.reporting_officers option[value='+val.value+']').prop('disabled',false);
            });
            $('.reporting_officers').select2({
                allowClear:true
            });
            

            $('#error_msg_id').removeClass('error').addClass('no-error');
            
            $.get('/getEmployeeById/' + employee_id, function (data) {
                console.log(data);
                //success data
                original_employee_id = employee_id;
                $('#inputEmployeeId').val(data.employee_id);
                $('#inputName').val(data.name);
                
                $('#inputEmail').val(data.email);
                $('#inputMobile1').val(data.mobileNumber1);
                $('#inputMobile2').val(data.mobileNumber2);
                $('#inputCountry').val(data.country);
                $('#inputState').val(data.state);
                $('#inputCity').val(data.city);
                $('#inputStreet_address_1').val(data.street_address_1);
                $('#inputStreet_address_2').val(data.street_address_2);
                $('#inputPostalCode').val(data.postal_code);
                $('#datepicker_DOB').val(data.dob);
                
                if(data.gender==1)
                    $('#radio_male').prop("checked", true);
                else
                    $('#radio_female').prop("checked", true);

                if(data.marital_status == 'single')
                    $('#radio_single').prop("checked", true);
                else if(data.marital_status == 'married')
                    $('#radio_married').prop("checked", true);
                else
                    $('#radio_divorced').prop("checked",true);
                
                $('#datepicker_anniversary').val(data.anniversary);
                $('#inputFatherName').val(data.father_name);
                $('#inputEducationalQualification').val(data.educational_qualification);
                $('#inputProfessionalQualification').val(data.professional_qualification);
                $('#inputExperience').val(data.experience);

                $('#inputCardNumber').val(data.card_number);
                $('#select_department').val(data.dept_id).change();
                $('#select_category').val(data.category_id).change();
                $('#select_branch').val(data.branch_id).change();
                $('#select_designation').val(data.designation_id).change();
                
                if(data.Permanent_Temporary ==0)
                    $('#radio_temporary').prop("checked",true);
                else if(data.Permanent_Temporary == 1)
                    $('#radio_provasion').prop("checked",true);
                else
                    $('#radio_permanent').prop('checked',true);
                
                $('#select_weekOffDay').val(data.week_off_day).change();
                $('#select_additionalOffDay').val(data.additional_off_day).change();
                var additional_off_week_array;
                if(data.additional_off_week !=null){
                    additional_off_week_array= data.additional_off_week.split(',');
                }
                $('#select_additionalOffWeek').val(additional_off_week_array).change();
                $('#select_shift_1').val(data.shift_1).change();
                $('#select_shift_2').val(data.shift_2).change();
                $('#select_shift_3').val(data.shift_3).change();
                $('#select_shift_4').val(data.shift_4).change();

                if(data.auto_shift==1 ||data.auto_shift == true)
                    $('#radio_autoSelectShift_yes').prop("checked", true);
                else
                    $('#radio_autoSelectShift_no').prop("checked", true);
                
                if(data.change_by_week==1)
                    $('#radio_changeByWeek_yes').prop("checked",true);
                else
                    $('#radio_changeByWeek_no').prop('checked',true);

                if(data.change_after_days ==1)
                    $('#radio_changeAfterDay_yes').prop("checked",true);
                else
                    $('#radio_changeAfterDay_no').prop("checked",true);
                
                $('#select_changed_on_day').val(data.changed_on_day).change();
                $('#select_half_day_shift').val(data.half_day_shift).change();
                $('#select_half_day_on').val(data.half_day_on).change();
                
                if(data.comp_off_applicable==1)
                    $('#radio_comp_off_yes').prop("checked",true);
                else
                    $('#radio_comp_off_no').prop('checked',true);
                if(data.overtime_applicable==1)
                    $('#radio_overtime_yes').prop("checked",true);
                else
                    $('#radio_overtime_no').prop('checked',true);
                
                $('#select_ReportingOfficer1').val(data.reporting_officer_1).change();
                $('#select_ReportingOfficer2').val(data.reporting_officer_2).change();
                $('#datepicker_joiningDate').val(data.joining_date);
                $('#datepicker_leavingDate').val(data.leaving_date);
                $('#inputReferredBy').val(data.referred_by);

                $('#input_ESI_number').val(data.ESI_number);
                $('#input_PF_number').val(data.PF_number);
                $('#input_UAN_number').val(data.UAN_number);
                $('#input_PAN_number').val(data.PAN_number);
                
                if(data.wage_type==1)
                    $('#radio_daily').prop("checked",true);
                else
                    $('#radio_monthly').prop('checked',true);
                
                $('#input_bank_name').val(data.bank_name);
                $('#input_IFSC_code').val(data.IFSC_code);
                $('#input_bank_branch').val(data.bank_branch);
                $('#input_account_number').val(data.bank_account_number);
                

                $('#btn_confirm').val("update");
                $('#btn_confirm').text("Update");
                $('#modal-title').text('Edit Employee');
                $('#modal-add').modal('show');
            }); 
        });

        //delete employee and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete an employee. Are you sure?')){
                var employee_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteEmployee/' + employee_id,
                    success: function (data) {
                        $("#employee" + employee_id).remove();
                        $(".reporting_officers option[value='"+employee_id+"']").remove();
                    },
                    error: function (data) {
                        alert("Something went wrong while deleting an employee!\nPlease refresh the page and try again!")
                        console.error('Error:', data.responseJSON);
                    }
                });
            }
            
        });

        function changed_reporting_officer1(selected){
            $.each($('#select_ReportingOfficer1 option[value]'),function(key,val){
                if(employee_id == val.value){
                    $('.reporting_officers option[value='+val.value+']').prop('hidden',true);
                }else
                    $('.reporting_officers option[value='+val.value+']').prop('hidden',false);
            });
            if(selected != "" && selected != null)
                $('#select_ReportingOfficer2 option[value="'+selected+'"]').prop('disabled',true);
            setTimeout(function () {
                $('.reporting_officers').select2({
                    allowClear: true
                });
            });
        }
        function changed_reporting_officer2(selected){
            $.each($('#select_ReportingOfficer2 option[value]'),function(key,val){
                if(employee_id == val.value){
                    $('.reporting_officers option[value='+val.value+']').prop('disabled',true);
                }else
                    $('.reporting_officers option[value='+val.value+']').prop('disabled',false);
            });
            if(selected != "" && selected != null)
                $('#select_ReportingOfficer1 option[value="'+selected+'"]').prop('disabled',true);
            setTimeout(function () {
                $('.reporting_officers').select2({
                    allowClear: true
                });
            });
        }

        
        //create new product / update existing product
        $("#btn_confirm").click(function (e) {
                    
            e.preventDefault(); 
            if($("#form_addEmployee_t1").valid() && $("#form_addEmployee_t2").valid() && $("#form_addEmployee_t3").valid() ){
                var type = "POST"; //for creating new resource
                var employee_id = $('#inputEmployeeId').val();
                var url = '/addEmployee'; // by default add shift
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                var additional_off_weeks_array = $('#select_additionalOffWeek').val();
                var additional_off_weeks_string = '';
                for(var i =0; i < additional_off_weeks_array.length; i++){
                    if(i>0)
                        additional_off_weeks_string +=',';
                    additional_off_weeks_string += additional_off_weeks_array[i];
                }
                var formData = {
                    company_id                  : $('#inputCompanyId').val(),
                    employee_id                 : $('#inputEmployeeId').val(),
                    name                        : $('#inputName').val(),
                    mobileNumber1               : $('#inputMobile1').val(),
                    mobileNumber2               : $('#inputMobile2').val(),
                    email                       : $('#inputEmail').val(),
                    country                     : $('#inputCountry').val(),
                    state                       : $('#inputState').val(),
                    city                        : $('#inputCity').val(),
                    street_address_1            : $('#inputStreet_address_1').val(),
                    street_address_2            : $('#inputStreet_address_2').val(),
                    postal_code                 : $('#inputPostalCode').val(),
                    dob                         : $('#datepicker_DOB').val(),
                    gender                      : $('#radio_male').prop("checked")==true?1:0,
                    marital_status              : $('#radio_single').prop("checked")==true?'single':$('#radio_married').prop("checked")==true?'married':'divorced',
                    anniversary                 : $('#datepicker_anniversary').val(),
                    father_name                 : $('#inputFatherName').val(),
                    educational_qualification   : $('#inputEducationalQualification').val(),
                    professional_qualification  : $('#inputProfessionalQualification').val(),
                    experience                  : $('#inputExperience').val(),

                    card_number                 : $('#inputCardNumber').val(),
                    dept_id                     : $('#select_department').val(),
                    category_id                 : $('#select_category').val(),
                    branch_id                   : $('#select_branch').val(),
                    designation_id              : $('#select_designation').val(),
                    Permanent_Temporary         : $('#radio_temporary').prop("checked")==true?0:$('#radio_provasion').prop("checked")==true?1:2,
                    week_off_day                : $('#select_weekOffDay').val(),
                    additional_off_day          : $('#select_additionalOffDay').val(),
                    additional_off_week         : additional_off_weeks_string,
                    shift_1                     : $('#select_shift_1').val()!="none"?$('#select_shift_1').val():null,
                    shift_2                     : $('#select_shift_2').val()!="none"?$('#select_shift_2').val():null,
                    shift_3                     : $('#select_shift_3').val()!="none"?$('#select_shift_3').val():null,
                    shift_4                     : $('#select_shift_4').val()!="none"?$('#select_shift_4').val():null,
                    auto_shift                  : $('#radio_autoSelectShift_yes').prop('checked')==true?1:0,
                    change_by_week              : $('#radio_changeByWeek_yes').prop("checked")==true?1:0,
                    change_after_days           : $('#radio_changeAfterDay_yes').prop("checked")==true?1:0,
                    changed_on_day              : $('#select_changed_on_day').val(),
                    half_day_shift              : $('#select_half_day_shift').val(),
                    half_day_on                 : $('#select_half_day_on').val(),
                    comp_off_applicable         : $('#radio_comp_off_yes').prop("checked")==true?1:0,
                    overtime_applicable         : $('#radio_overtime_yes').prop("checked")==true?1:0, 
                    reporting_officer_1         : $('#select_ReportingOfficer1').val(),
                    reporting_officer_2         : $('#select_ReportingOfficer2').val(),
                    joining_date                : $('#datepicker_joiningDate').val(),
                    leaving_date                : $('#datepicker_leavingDate').val(),
                    referred_by                 : $('#inputReferredBy').val(),

                    ESI_number                  : $('#input_ESI_number').val(),
                    PF_number                   : $('#input_PF_number').val(),
                    UAN_number                  : $('#input_UAN_number').val(),
                    PAN_number                  : $('#input_PAN_number').val(),
                    wage_type                   : $('#radio_daily').prop("checked")==true?0:1,
                    bank_name                   : $('#input_bank_name').val(),
                    IFSC_code                   : $('#input_IFSC_code').val(),
                    bank_branch                 : $('#input_bank_branch').val(),
                    bank_account_number         : $('#input_account_number').val()
        
                }
                //used to determine the http verb to use [add=POST], [update=PUT]
                var state = $('#btn_confirm').val();
                if(state=="add"){
                    type = "POST"; 
                    url = '/addEmployee';
                }else if (state == "update"){
                    type = "PUT"; //for updating existing resource
                    url = '/updateEmployee/' + original_employee_id;
                }
                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        var employee = '<tr id="employee'+data.orig_data.employee_id +'"><td>' +
                                            + data.orig_data.name + '</td><td>'
                                            + data.orig_data.father_name + '</td><td>' 
                                            + data.orig_data.employee_id + '</td><td>' 
                                            + data.orig_data.card_number + '</td><td>' 
                                            + data.department_name + '</td><td>' 
                                            + data.designation_name + '</td><td>' 
                                            + data.branch_name + '</td><td>' 
                                            + data.shift_name + '</td>' ;
                        employee += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.orig_data.employee_id + '"><i class="fa fa-edit"></i></button>';
                        employee += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.orig_data.employee_id + '"><i class="fa fa-trash"></i></button></td></tr>';
                        if (state == "add"){ //if user added a new record
                            $('#employees-list').prepend(employee);
                            var newOption = new Option(data.orig_data.name +' ['+data.designation_name+']', data.orig_data.employee_id, false, false);
                            $('.reporting_officers').append(newOption).trigger('change');
                        }else{ //if user updated an existing record
                            $("#employee" + original_employee_id).replaceWith( employee );
                        }
                        $('#form_addEmployee').trigger("reset");
                        $('#modal-add').modal('hide');
                    },
                    error: function (data) {
                        //alert('Error: '+JSON.stringify(data['responseJSON']));
                        console.log('Error:', data);
                    }
                });    
            }else{
                alert("Data Missing!\n\nPlease verify and try again");
            }
        });
        //Function to mark the select valid as some value is selected
        $('.select2').on('change', function() {
            $(this).valid();
        });
        $('.datePicker').on('change',function(){
            $(this).valid();
        });
        $('.nav-tabs li.disabled > a[data-toggle=tab]').on('click', function(e) {
            if(state=='add'){
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
        $("#form_addEmployee_t1").validate({
            ignore: "",
        });
        
        $("#form_addEmployee_t3").validate({
            ignore: ""
        });
        $("#form_addEmployee_t2").validate({
            ignore: "",
            //put error message behind each form element
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (element.parent('.input-group').length) { 
                    error.insertAfter(element.parent());      // radio/checkbox?
                } else if (element.hasClass('select2')) {     
                    error.insertAfter(element.next('span'));  // select2
                } else {                                      
                    error.insertAfter(element);               // default
                }; 
            }
        });
        $(document).on('focusin', '#inputEmployeeId', function(){
                $(this).data('val', $(this).val());
            }).on('change','#inputEmployeeId', function(){
                var current = $(this).val();
                if(state=="update"){
                    if($('[id=employee'+employee_id+']').length>0 && employee_id !=current && $('[id=employee'+current+']').length>0){
                        $('#error_employee_id').removeClass('no-error').addClass('error');
                        validated = false;
                    }
                    else{
                        $('#error_employee_id').removeClass('error').addClass('no-error');
                        validated = true;
                    }
                }else if(state=="add"){
                    if($('[id=employee'+current+']').length>0){
                        $('#error_employee_id').removeClass('no-error').addClass('error');
                        validated = false;
                    }
                    else{
                        $('#error_employee_id').removeClass('error').addClass('no-error');
                        validated = true;
                    }
                }
        });
        $(document).on('change','#inputCardNumber', function(){
            var current = $(this).val();
            $.get('/findCardNumber/'+current, function(result){
                if(result=="duplicate"){
                    validated = false;
                    $('#error_cardNumber').removeClass('no-error').addClass('error');
                }else{
                    validated = true;
                    $('#error_cardNumber').removeClass('error').addClass('no-error');
                }
            });
        });
    </script>
@endsection