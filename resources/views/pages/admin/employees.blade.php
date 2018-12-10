@extends('layouts.master')

@section('content')

<!-- Custom Tabs -->
<div class="row" id="addNewEmployee">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Personal Info</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Official Info</a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Bank Info</a></li>
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
        </ul>
        <div class="tab-content" style="padding:25px">
            <div class="tab-pane active" id="tab_1">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputEmployeeId" class="col-sm-4 control-label">Employee ID <span class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmployeeId" placeholder="Employee ID">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-4 control-label">Name <span class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Gender <span class="required">*</span></label>
                                        <div class="col-sm-8">
                                            <label for="radio_male">Male
                                                <input type="radio" id="radio_male" name="gender" value="1" class="flat-red" checked>
                                            </label>
                                            <label for="radio_female">Female
                                                <input type="radio" id="radio_female" name="gender" value="0" class="flat-red">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div><label for="datepicker_DOB" class="col-sm-4 control-label">D.O.B <span class="required">*</span></label></div>
                                        <div class="col-sm-8">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="datepicker_DOB">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Maritial Status <span class="required">*</span></label>
                                        <div class="col-sm-8">
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
                                <div class="col-sm-6">
                                    <label>Employee Photo</label>
                                    <div class="row">
                                        <img id="blah" src="{{asset('img/avatar.png')}}" alt="your image" style="width:150px;height:150px;border:1px solid black">
                                    </div>
                                    <div class="row">
                                    <!-- <form action="/uploadfile" method="post" enctype="multipart/form-data"> -->
                                            @csrf
                                            <div >
                                                <input type="file" class="form-control-file" name="fileToUpload" id="inputEmployeePhoto" aria-describedby="fileHelp">
                                                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                                            </div>
                                            
                                        <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group notSingle">
                                <label for="inputSpouseName" class="col-sm-2 control-label">Spouse Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputSpouseName" placeholder="Spouse Name">
                                </div>
                            </div>
                            <div class="form-group notSingle">
                                <div><label for="datepicker_anniversary" class="col-sm-2 control-label">Anniversary Date</label></div>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker_anniversary">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="inputFatherName" class="col-sm-2 control-label">Father Name <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputFatherName" placeholder="Father's Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEducationalQualification" class="col-sm-2 control-label">Educational Qualification <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputEducationalQualification" placeholder="Educational Qualification">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputProfessionalQualification" class="col-sm-2 control-label">Professional Qualification <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputProfessionalQualification" placeholder="Professional Qualification">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">Experience <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputExperience" placeholder="Experience (in years)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAddress" class="col-sm-2 control-label">Address <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputAddress" placeholder="Please provide full address"></textarea>
                                </div>
                            </div> 
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2" >
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputCardNumber" class="col-sm-2 control-label">Card Number</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="inputCardNumber" placeholder="Employee Card Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="select_department" class="col-sm-2 control-label">Department</label>
                                <select id="select_department" class="form-control col-sm-10 select2" style="width: 81%;height:35px;margin-left:15px;">
                                    <option selected="selected">Accounts</option>
                                    <option>Marketing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select_category" class="col-sm-2 control-label">Category</label>
                                <select id="select_category" class="form-control col-sm-10 select2" style="width: 81%;height:35px;margin-left:15px;">
                                    <option selected="selected">Category 1</option>
                                    <option>Category 2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select_branch" class="col-sm-2 control-label">Branch</label>
                                <select id="select_branch" class="form-control col-sm-10 select2" style="width: 81%;height:35px;margin-left:15px;">
                                    <option selected="selected">Delhi</option>
                                    <option>Noida</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select_designation" class="col-sm-2 control-label">Designation</label>
                                <select id="select_designation" class="form-control col-sm-10 select2" style="width: 81%;height:35px;margin-left:15px;">
                                    <option selected="selected">Manager</option>
                                    <option>Staff</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-10">
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
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="input_ESI_number" class="col-sm-2 control-label">ESI Number</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_ESI_number" placeholder="ESI Number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input_PF_number" class="col-sm-2 control-label">PF Number</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_PF_number" placeholder="PF Number">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input_UAN_number" class="col-sm-2 control-label">UAN Number</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_UAN_number" placeholder="UAN Number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Wage Type</label>
                            <div class="col-sm-10">
                                <label for="radio_daily">Daily
                                    <input type="radio" id="radio_daily" name="type" value="0" class="flat-red" checked>
                                </label>
                                <label for="radio_monthly">Monthly
                                    <input type="radio" id="radio_monthly" name="type" value="1" class="flat-red">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_bank_name" class="col-sm-2 control-label">Bank Name</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_bank_name" placeholder="Bank Name">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="input_IFSC_code" class="col-sm-2 control-label">IFSC Code</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_IFSC_code" placeholder="IFSC Code">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input_bank_branch" class="col-sm-2 control-label">Branch</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_bank_branch" placeholder="Branch">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input_account_number" class="col-sm-2 control-label">Account Number</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_account_number" placeholder="Account Number">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
                
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
</div>


<!-- nav-tabs-custom -->
@endsection

@section('footer')
<script type="text/javascript">
    $(function () {
        $('#datepicker_DOB').datepicker({
            format: "mm/dd/yy",
            weekStart: 0,
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            rtl: true,
            orientation: "auto"
        });
        $('#datepicker_anniversary').datepicker({
            format: "mm/dd/yy",
            weekStart: 0,
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            rtl: true,
            orientation: "auto"
        });
        $(document).ready(function(){
            $('input[name="maritial_status"]').click(function(){
                var inputValue = $(this).attr("value");
                if(inputValue !='single'){
                    $('.notSingle').show();
                }
                else{
                    $('.notSingle').hide();
                }
                
            });
            $addButtonClicked = false;
        });
        function readURL(input) {

            if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            }
        }

        $("#inputEmployeePhoto").change(function() {
            readURL(this);
        });
    });
</script>
@endsection