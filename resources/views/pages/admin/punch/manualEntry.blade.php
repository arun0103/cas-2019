@extends('layouts.master')
@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datepicker/datepicker3.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('js/plugins/bootstrap-datetimepicker-0.0.11/css/bootstrap-datetimepicker.min.css')}}"> -->
    
    <link rel="stylesheet" href="{{asset('js/plugins/timepicker/bootstrap-timepicker.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="messageBox"></div>
    <div class="loading">Loading&#8230;</div>
    <h3>Manual Punch Entry</h3>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group" >
                <label for="select_branch">Branch <span class="required">*</span></label>
                <div class="row">
                    <div class="col-sm-12" id="branch_div">
                        <select id="select_branch" class="form-control select2 percent100"  data-placeholder="Select Branch" name="selectedBranchView" onchange="populateEmployee(this.value)" required>
                            <option></option>    
                            @foreach($branches as $branch)
                                <option value="{{$branch->branch_id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                        <span id="error_branch" class="no-error">Required!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group" >
                <label for="select_employee">Employee <span class="required">*</span></label>
                <div class="row">
                    <div class="col-sm-12">
                        <select id="select_employee" class="form-control select2 percent100"  data-placeholder="Select an Employee" name="selectedEmployeeView" required>    
                            <option></option> 
                        </select>
                        <span id="error_employee" class="no-error">Required!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="datepicker_punch_date" class="control-label">Punch Date <span class="required">*</span></label>
                <div class="input-group">
                    <div class="input-group-addon left-addon">
                        <i class="fa fa-calendar"></i>
                        <input type="text" class="form-control pull-right" id="datepicker_punch_date" autocomplete="off" placeholder="Select Date">
                    </div>
                    <span id="error_date" class="no-error">Required!</span>
                </div>
            </div>
        </div>
        
        <div class="col-sm-2" style="margin-top:30px">
            <button class="btn btn-primary" id="btn_search">Search</button>
        </div>
    </div>
    <div class="modal fade modal-fullscreen" id="modal-manual_entry">
        <form id="form_manualEntrySearch" class="form-horizontal" method="post" action="" autocomplete="off">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add/Edit Punch Details of </h4>
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <input type="hidden" id="inputRosterId">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="select_shift" class="control-label">Shift <span class="required">*</span></label>
                                    <select id="select_shift" class="form-control select2" data-placeholder="Select a Shift">
                                        <option></option>
                                        @foreach($shifts as $shift)
                                            <option value="{{$shift->shift_id}}">
                                                <div>
                                                    <h4>{{$shift->name}}</h4><br/>
                                                    <span>[{{$shift->start_time}} - {{$shift->end_time}}]</span>
                                                </div>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Day Status <span class="required">*</span></label>
                                    <select id="select_dayStatus" class="form-control select2">
                                        <option value="W">Work</option>
                                        <option value="H">Holiday</option>
                                        <option value="O">Weekly Off</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Punch 1</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch1_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_1" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Punch 2</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch2_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_2" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Punch 3</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch3_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_3" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Punch 4</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch4_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_4" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Punch 5</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch5_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <span class="fa fa-clock-o"></span>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_5" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Punch 6</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control pull-right date punchDates" id="datepicker_punch6_date" autocomplete="off" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="bootstrap-timepicker">
                                            <div class="input-group">
                                                <div class="input-group-addon left-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                    <input type="text" class="form-control timepicker pull-right" id="time_punch_6" placeholder="No Data">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label><u>Gate Pass</u></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <input type="checkbox" name="hasGatePass1" id="hasGatePass1" onchange="toggleDisable(1)"><label for="hasGatePass1">1st Half</label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="gatePass1_out">Out Time</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon left-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                                <input type="text" class="form-control timepicker pull-right" id="gatePass1_out" disabled>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="gatePass1_in">In Time</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon left-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                                <input type="text" class="form-control timepicker pull-right" id="gatePass1_in" disabled>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="inputHours1" class="control-label">Hours</label>
                                                    <input type="number" class="form-control" id="inputHours1" placeholder="Hours" name="gp1_hours" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <input type="checkbox" name="hasGatePass2" id="hasGatePass2" onchange="toggleDisable(2)"><label for="hasGatePass2">2nd Half</label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="gatePass2_out">Out Time</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon left-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                                <input type="text" class="form-control timepicker pull-right" id="gatePass2_out" disabled>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label for="gatePass2_in">In Time</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon left-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                                <input type="text" class="form-control timepicker pull-right" id="gatePass2_in" disabled>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="inputHours2" class="control-label">Hours</label>
                                                    <input type="number" class="form-control" id="inputHours2" placeholder="Hours" name="gp2_hours" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label><u>Half</u></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="input_half_1">1st Half</label>
                                        <input type="text" disabled id="input_half_1" class="form-control">
                                    </div>
                                    &nbsp;
                                    <div class="col-sm-12">
                                        <label for="input_half_2">2nd Half</label>
                                        <input type="text" disabled id="input_half_2" class="form-control">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn_save" value="view">Save</button>
                        <button type="button" class="btn btn-danger" id="btn_delete" value="view">Delete</button>
                        <button type="button" class="btn btn-default pull-left btn-close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>



@endsection

@section('footer')
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('js/plugins/timepicker/bootstrap-timepicker.js')}}"></script>
    <!-- <script src="{{asset('js/plugins/bootstrap-datetimepicker-0.0.11/js/bootstrap-datetimepicker.min.js')}}"></script> -->
    
    <script>
        var dataFrom, id;
        var branch_id, emp_id;
        $(document).ready(function () {
            $('.select2').select2();
            $('#datepicker_punch_date').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 0,
                autoclose: true,
                todayHighlight: true,
                orientation: "auto",
                endDate: '+0d'
            });
            $('.timepicker').timepicker({
                showInputs: false,
                minuteStep: 1
            });
            $('#form_manualEntrySearch').trigger("reset");
            $(".timepicker").val("");
            $('.loading').hide();
        });
        function validate_search(){
            var validated = true;
            if($('#select_branch').val() ==""){
                $('#error_branch').removeClass("no-error").addClass('error');
                validated = false;
            }else{
                $('#error_branch').removeClass('error').addClass('no-error');
            }
            if($('#select_employee').val() == ""){
                validated = false;
                $('#error_employee').removeClass("no-error").addClass('error');
            }else{
                $('#error_employee').removeClass('error').addClass('no-error');
            }
            if($('#datepicker_punch_date').val() ==""){
                validated = false;
                $('#error_date').removeClass('no-error').addClass('error');
            }else{
                $('#error_date').removeClass('error').addClass('no-error');
            }
            return validated;
        }
        $(document).on('change','#select_branch',function(){
            $('#error_branch').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#select_employee',function(){
            $('#error_employee').removeClass('error').addClass('no-error');
        });
        $(document).on('change','#datepicker_punch_date',function(){
            $('#error_date').removeClass('error').addClass('no-error');
        });
        $('#btn_search').click(function(){
            if(validate_search()){
                branch_id = $('#select_branch').val();
                emp_id = $('#select_employee').val();
                var date = $('#datepicker_punch_date').val();
                $('#modal-title').text("Add/Edit Punch Detail of "+date);
                $('#form_manualEntrySearch').trigger("reset");
                //alert('/getPunchDetails/'+branch_id +'/'+emp_id+'/'+date);
                $.get('/getRosterDetails/'+branch_id +'/'+emp_id+'/'+date, function(rosterData){
                    if(rosterData != ""){
                        id = rosterData.id;
                        $('#inputRosterId').val(id);
                        dataFrom = "roster";
                        //alert(JSON.stringify(rosterData));
                        $('#select_shift').val(rosterData.shift_id).trigger('change');
                        $('#select_dayStatus').val(rosterData.is_holiday).trigger('change'); 
                        $('#input_half_1').val(rosterData.final_half_1).trigger('change');
                        $('#input_half_2').val(rosterData.final_half_2).trigger('change');
                        $.get('/getPunchDetails/'+branch_id +'/'+emp_id+'/'+date, function(punchData){
                            $(".punchDates").datepicker('remove');
                            var maxPunchDate = new Date(date);
                            maxPunchDate.setDate(maxPunchDate.getDate()+1);
                            $( ".punchDates" ).datepicker({ 
                                defaultDate: date,
                                format: "yyyy-mm-dd",
                                weekStart: 0,
                                autoclose: true,
                                todayHighlight: true,
                                orientation: "auto",
                                startDate: date,
                                endDate: maxPunchDate,
                                
                            });
                        if(punchData != ""){
                            dataFrom = "punch";
                            id = punchData.id;
                            //alert(JSON.stringify(punchData));
                            $('#select_shift').val(punchData.shift_code).trigger('change');
                            $('#select_dayStatus').val(punchData.status).trigger('change');
                            if(punchData.punch_1 !=null){
                                $('#datepicker_punch1_date').val(getDate(punchData.punch_1)).trigger('change');
                                $('#time_punch_1').val(getTime(punchData.punch_1)).trigger('change');
                            }else{
                                $('#datepicker_punch1_date').val(date).trigger('change');
                            }
                            if(punchData.punch_2 !=null){
                                $('#datepicker_punch2_date').val(getDate(punchData.punch_2)).trigger('change');
                                $('#time_punch_2').val(getTime(punchData.punch_2)).trigger('change');
                            }else{
                                $('#datepicker_punch2_date').val(date).trigger('change');
                            }
                            if(punchData.punch_3 !=null){
                                $('#datepicker_punch3_date').val(getDate(punchData.punch_3)).trigger('change');
                                $('#time_punch_3').val(getTime(punchData.punch_3)).trigger('change');
                            }else{
                                $('#datepicker_punch3_date').val(date).trigger('change');
                            }
                            if(punchData.punch_4 !=null){
                                $('#datepicker_punch4_date').val(getDate(punchData.punch_4)).trigger('change');
                                $('#time_punch_4').val(getTime(punchData.punch_4)).trigger('change');
                            }else{
                                $('#datepicker_punch4_date').val(date).trigger('change');
                            }
                            if(punchData.punch_5 !=null){
                                $('#datepicker_punch5_date').val(getDate(punchData.punch_5)).trigger('change');
                                $('#time_punch_5').val(getTime(punchData.punch_5)).trigger('change');
                            }else{
                                $('#datepicker_punch5_date').val(date).trigger('change');
                            }
                            if(punchData.punch_6 !=null){
                                $('#datepicker_punch6_date').val(getDate(punchData.punch_6)).trigger('change');
                                $('#time_punch_6').val(getTime(punchData.punch_6)).trigger('change');
                            }else{
                                $('#datepicker_punch6_date').val(date).trigger('change');
                            }
                            if(punchData.half_1_gate_pass ==1){
                                $('#hasGatePass1').prop('checked',true);
                                $('#gatePass1_out').prop('disabled',false);
                                $('#gatePass1_out').prop('required','required');
                                $('#gatePass1_in').prop('disabled',false);
                                $('#gatePass1_in').prop('required','required');
                                $('#inputHours1').prop('disabled',false);
                                $('#inputHours1').prop('required','required');
                                $('#gatePass1_out').val(punchData.half_1_gp_out).trigger('change');
                                $('#gatePass1_in').val(punchData.half_1_gp_in).trigger('change');
                                $('#inputHours1').val(punchData.half_1_gp_hrs).trigger('change');
                            }
                            if(punchData.half_2_gate_pass ==1){
                                $('#hasGatePass2').prop('checked',true);
                                $('#gatePass2_out').prop('disabled',false);
                                $('#gatePass2_out').prop('required','required');
                                $('#gatePass2_in').prop('disabled',false);
                                $('#gatePass2_in').prop('required','required');
                                $('#inputHours2').prop('disabled',false);
                                $('#inputHours2').prop('required','required');
                                $('#gatePass2_out').val(punchData.half_2_gp_out).trigger('change');
                                $('#gatePass2_in').val(punchData.half_2_gp_in).trigger('change');
                                $('#inputHours2').val(punchData.half_2_gp_hrs).trigger('change');
                            }
                            $('#inputRemarks').val(punchData.remarks).trigger('change');
                            $('#input_half_1').val(punchData.final_half_1).trigger('change');
                            $('#input_half_2').val(punchData.final_half_2).trigger('change');
                            $('#btn_confirm_delete').prop('hidden',false); 
                            $('#modal-manual_entry').modal('show');
                        }else{
                            //alert("finding roster details");
                            $('#datepicker_punch1_date').val(date).trigger('change');
                            $('#datepicker_punch2_date').val(date).trigger('change');
                            $('#datepicker_punch3_date').val(date).trigger('change');
                            $('#datepicker_punch4_date').val(date).trigger('change');
                            $('#datepicker_punch5_date').val(date).trigger('change');
                            $('#datepicker_punch6_date').val(date).trigger('change');
                            
                        }
                    });
                        $('#btn_confirm_delete').prop('hidden',true);                       
                        $('#modal-manual_entry').modal('show');
                    }else{
                        alert("No Data found in Roster.. \n\nPlease create Roster first!")
                    }
                }); 
                
            
            }else{
                
            }
        });
        $('#btn_save').click(function(){
            var formData = {
                'roster_id'         :$('#inputRosterId').val(),
                'branch_id'         :branch_id,
                'employee_id'       :emp_id,
                'shift_id'          :$('#select_shift').val(),
                'status'            :$('#select_dayStatus').val(),
                'punch_date'        :$('#datepicker_punch_date').val(),
                'punch_1'           :$('#time_punch_1').val()!="" && $('#datepicker_punch1_date').val()!=""?makeDateTime($('#datepicker_punch1_date').val(),$('#time_punch_1').val()):null,
                'punch_2'           :$('#time_punch_2').val()!="" && $('#datepicker_punch2_date').val()!=""?makeDateTime($('#datepicker_punch2_date').val(),$('#time_punch_2').val()):null,
                'punch_3'           :$('#time_punch_3').val()!="" && $('#datepicker_punch3_date').val()!=""?makeDateTime($('#datepicker_punch3_date').val(),$('#time_punch_3').val()):null,
                'punch_4'           :$('#time_punch_4').val()!="" && $('#datepicker_punch4_date').val()!=""?makeDateTime($('#datepicker_punch4_date').val(),$('#time_punch_4').val()):null,
                'punch_5'           :$('#time_punch_5').val()!="" && $('#datepicker_punch5_date').val()!=""?makeDateTime($('#datepicker_punch5_date').val(),$('#time_punch_5').val()):null,
                'punch_6'           :$('#time_punch_6').val()!="" && $('#datepicker_punch6_date').val()!=""?makeDateTime($('#datepicker_punch6_date').val(),$('#time_punch_6').val()):null,
                'half_1_gate_pass'  :$('#hasGatePass1').prop("checked")?1:0,
                'half_1_gp_out'     :$('#gatePass1_out').val(),
                'half_1_gp_in'      :$('#gatePass1_in').val(),
                'half_1_gp_hrs'     :$('#inputHours1').val(),
                'half_2_gate_pass'  :$('#hasGatePass2').prop("checked")?1:0,
                'half_2_gp_out'     :$('#gatePass2_out').val(),
                'half_2_gp_in'      :$('#gatePass2_in').val(),
                'half_2_gp_hrs'     :$('#inputHours2').val(),
                'remarks'           :$('#inputRemarks').val(),
            }
            switch(dataFrom){
                case "punch":
                    if(validateForm(formData)){
                        updatePunchRecord(formData, id);
                    }else{
                        alert('Please correct invalid fields marked with red border. Then try again!')
                    }    
                    break;
                case "roster":
                    if(validateForm(formData)){
                        insertPunchRecord(formData);
                    }else{
                        alert('Please correct invalid fields marked with red border. Then try again!')
                    }   
                    break;
            }
        });
        function validateForm(formData){
            var valid = true;
            if(formData.shift_id == ""){
                $('#select_shift').addClass('error error-border');
                valid = false;
            }else{
                $('#select_shift').removeClass('error error-border');
            }
            if(formData.status == ""){
                $('#select_dayStatus').addClass('error error-border');
                valid = false;
            }else{
                $('#select_dayStatus').removeClass('error error-border');
            }
            if(formData.punch_date == ""){
                $('#datepicker_punch_date').addClass('error error-border');
                valid = false;
            }else{
                $('#datepicker_punch_date').removeClass('error error-border');
            }
            if(formData.half_1_gate_pass == 1 && (formData.half_1_gp_out == "" || formData.half_1_gp_in == "" || formData.half_1_gp_hrs == "")){
                valid = false;
                if(formData.half_1_gp_out == "")
                    $('#gatePass1_out').addClass('error error-border');
                else
                    $('#gatePass1_out').removeClass("error error-border");
                if(formData.half_1_gp_in == "")
                    $('#gatePass1_in').addClass('error error-border');
                else
                    $('#gatePass1_in').removeClass("error error-border");
                if(formData.half_1_gp_hrs == "")
                    $('#inputHours1').addClass('error error-border');
                else
                    $('#inputHours1').removeClass("error error-border");
            }else{
                $('#gatePass1_out').removeClass("error error-border");
                $('#gatePass1_in').removeClass("error error-border");
                $('#inputHours1').removeClass("error error-border");
            } 
            if(formData.half_2_gate_pass == 1 && (formData.half_2_gp_out == "" || formData.half_2_gp_in == "" || formData.half_2_gp_hrs == "")){
                valid = false;
                if(formData.half_2_gp_out == "")
                    $('#gatePass2_out').addClass('error error-border');
                else
                    $('#gatePass2_out').removeClass('error error-border');
                if(formData.half_2_gp_in == "")
                    $('#gatePass2_in').addClass('error error-border');
                else
                    $('#gatePass2_in').removeClass('error error-border');
                if(formData.half_2_gp_hrs == "")
                    $('#inputHours2').addClass('error error-border');
                else
                    $('#inputHours2').removeClass('error error-border');
            }else{
                $('#gatePass2_out').removeClass('error error-border');
                $('#gatePass2_in').removeClass('error error-border');  
                $('#inputHours2').removeClass('error error-border');  
            }
            var punch_1 = null, punch_2 = null, punch_3 = null, punch_4 = null, punch_5 = null, punch_6 = null;
            if($('#time_punch_1').val() !=""){
                punch_1 = makeDateTime($('#datepicker_punch1_date').val(),$('#time_punch_1').val());
            }else{
                if($('#time_punch_2').val() !="" || $('#time_punch_3').val() !="" || $('#time_punch_4').val() !="" 
                    || $('#time_punch_5').val() !="" || $('#time_punch_6').val() !=""){
                        valid = false;
                        alert('ERROR: Punch 1 is empty!');
                        $('#time_punch_1').addClass('error error-border');
                }
            }
            if($('#time_punch_2').val() !=""){
                punch_2 = makeDateTime($('#datepicker_punch2_date').val(),$('#time_punch_2').val());
                if(punch_2 < punch_1){
                    alert('Punch 2 is less than punch 1');
                    valid= false;
                }
            }else{
                if($('#time_punch_3').val() !="" || $('#time_punch_4').val() !="" 
                    || $('#time_punch_5').val() !="" || $('#time_punch_6').val() !=""){
                        valid = false;
                        alert('ERROR: Punch 2 is empty!');
                        $('#time_punch_2').addClass('error error-border');
                }
            }
            if($('#time_punch_3').val() !=""){
                punch_3 = makeDateTime($('#datepicker_punch3_date').val(),$('#time_punch_3').val());
                if(punch_3 < punch_2){
                    alert('Punch 3 is less than punch 2');
                    valid= false;
                }
            }else{
                if($('#time_punch_4').val() !="" || $('#time_punch_5').val() !="" || $('#time_punch_6').val() !=""){
                        valid = false;
                        alert('ERROR: Punch 3 is empty!');
                        $('#time_punch_3').addClass('error error-border');
                }
            }
            if($('#time_punch_4').val() !=""){
                punch_4 = makeDateTime($('#datepicker_punch4_date').val(),$('#time_punch_4').val());
                if(punch_4 < punch_3){
                    valid = false;
                    alert('Punch 4 is less than punch 3');
                }
            }else{
                if($('#time_punch_5').val() !="" || $('#time_punch_6').val() !=""){
                        valid = false;
                        alert('ERROR: Punch 4 is empty!');
                        $('#time_punch_4').addClass('error error-border');
                }
            }
            if($('#time_punch_5').val() !=""){
                punch_5 = makeDateTime($('#datepicker_punch5_date').val(),$('#time_punch_5').val());
                if(punch_5 < punch_4){
                    valid = false;
                    alert('Punch 5 is less than punch 4');
                }
            }else{
                if($('#time_punch_6').val() !=""){
                        valid = false;
                        alert('ERROR: Punch 5 is empty!');
                        $('#time_punch_5').addClass('error error-border');
                }
            }
            if($('#time_punch_6').val() !=""){
                punch_6 = makeDateTime($('#datepicker_punch6_date').val(),$('#time_punch_6').val());
                if(punch_6 < punch_5){
                    valid = false;
                    alert('Punch 6 is less than punch 5');
                }
            }

            
            // if($('#time_punch_6').val() != ""){
            //     if($('#time_punch_5').val() == "" ||$('#time_punch_4').val() == "" ||$('#time_punch_3').val() == "" ||$('#time_punch_2').val() == "" ||$('#time_punch_1').val() == ""){
            //         valid = false;
            //     }else{
            //         if($('#time_punch_5').val() == "")
            //     }
            // }
            // if($('#datepicker_punch1_date').val()!=""){
            //     if($('#time_punch_1').val()!=""){
            //         $('#time_punch_1').removeClass('error error-border');
            //         //alert("Validation 1 entered");
            //         punch_1 = makeDateTime($('#datepicker_punch1_date').val(),$('#time_punch_1').val());
            //     // alert("Validate 1: "+punch_1);
            //     }else{
            //         $('#time_punch_1').addClass('error error-border');
            //         valid = false;
            //     }
            // }else{
            //     if($('#time_punch_1').val()!=""){
            //         $('#datepicker_punch1_date').addClass('error error-border');
            //         valid = false;
            //     }else{
            //         $('#datepicker_punch1_date').removeClass('error error-border');
            //     }
            // }
            
            // if($('#datepicker_punch2_date').val()!=""){
            //     if($('#time_punch_2').val()!=""){
            //         $('#time_punch_2').removeClass('error error-border');
            //         punch_2 = makeDateTime($('#datepicker_punch2_date').val(),$('#time_punch_2').val());
            //         // if(punch_2 >punch_1){
            //         //     alert('OK');
            //         // }else{
            //         //     alert('Punch 2 must be greater than Punch 1');
            //         // }
            //     }else{
            //         $('#time_punch_2').addClass('error error-border');
            //         valid = false;
            //     }
            // }else{
            //     if($('#time_punch_2').val()!=""){
            //         $('#datepicker_punch2_date').addClass('error error-border');
            //         valid = false;
            //     }else{
            //         $('#datepicker_punch2_date').removeClass('error error-border');
            //     }
            // }
            return valid;
        }
        $('#btn_delete').click(function(){
            if(confirm("Are you sure, you want to delete the whole record?")){
                $('#modal-manual_entry').modal('hide');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deletePunchRecord/' + id,
                    success: function (data) {
                        alert('Deleted!');
                        var value = '<div class="alert alert-success alert-dismissible">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                    '<h4><i class="icon fa fa-check-circle"></i> Alert!</h4> Deleted</div>"';
                        $('.messageBox').val(value);
                        $('.messageBox').show();
                        
                    },
                    error: function (data) {
                        alert('Could not delete!!!');
                        var value = '<div class="alert alert-error alert-dismissible">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                                    '<h4><i class="icon fa fa-warning-circle"></i> Alert!</h4> Unable to delete</div>"';
                        $('.messageBox').val(value);
                        $('.messageBox').show();
                        console.error('Error:', data.responseJSON);
                    }
                });
            }

        });
        function updatePunchRecord(formData, recordID){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //e.preventDefault(); 
            $.ajax({
                type : 'POST',
                url: '/updatePunchRecord/'+id,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $('#modal-manual_entry').modal('hide');
                    alert('Saved');
                    //updateRoster(data);
                },
                error: function (data) {
                    //alert('Error: '+JSON.stringify(data['responseJSON']));
                    console.log('Error:', data);
                }
            });
        }
        function updateRoster(formData){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //e.preventDefault(); 
            $.ajax({
                type : 'PUT',
                url: '/updateRosterDetails',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    alert('Roster updated!');
                },
                error: function (data) {
                    alert('Error: Please REFRESH the page and Try again !');
                }
            });
        }
        function insertPunchRecord(formData){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //e.preventDefault(); 
            $.ajax({
                type : 'POST',
                url: '/insertPunchRecord',
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $('#modal-manual_entry').modal('hide');
                    alert('Saved');
                    //updateRoster(data);
                },
                error: function (data) {
                    //alert('Error: '+JSON.stringify(data['responseJSON']));
                    console.log('Error:', data);
                }
            });
        }
        function makeDateTime(date, time){
            var dateTime = date;
            //alert(date);
            if(time!=""){
                if(time.substr(6,2)=="PM"){
                    if(time.substr(0,2)!="12"){
                        dateTime += " "+(parseInt(time.substr(0,2))+12);
                        dateTime += time.substr(2,3)+":00";
                    }else{
                        dateTime += " " + time.substr(0,5)+":00";
                    }
                }else{
                    if(time.substr(0,2)=="12"){
                        dateTime += " 00"+ time.substr(2,3)+":00";
                    }else{
                        dateTime += " "+time.substr(0,5)+":00";
                    }
                }
            }
            //alert(dateTime);
            return dateTime;
        }
        function getTime(fullDateTime){
            //alert("Getting time");
            var time = fullDateTime.substr(11,5);
            var hr = time.substr(0,2);
            //alert("Time: "+time);
            if(parseInt(hr)>12){
                //alert(time);
                hr = parseInt(hr)-12;
                time = hr>9?hr+time.substr(2,3)+" PM": "0"+hr+time.substr(2,3)+" PM";
                //alert(time + " hr:"+hr);
            }else{
                time = time.substr(0,5)+ " AM";
                //alert("Else part: "+time);
            }
            return time;
        }
        function getDate(fullDateTime){
            var date = fullDateTime.substr(0,10);
            return date;
        }
        function toggleDisable(id){
            var value = $('#hasGatePass'+id).prop("checked");  
            $('#gatePass'+id+'_out').prop('disabled', !value);
            $('#gatePass'+id+'_in').prop('disabled', !value);
            $('#inputHours'+id).prop('disabled', !value);
        }

        function populateEmployee(branch){
            $('.loading').show();
            branch_id = branch;
            $.get("/employees/branch/"+branch, function(data){
                $("#select_employee").empty();
                $("#select_employee").append($('<option></option>')).trigger('change');
                for(var i=0;i<data.length;i++){
                    var newEmployee = $('<option value="'+data[i].employee_id+'">'+data[i].name+'</option>');
                    $('#select_employee').append(newEmployee).trigger('change');
                }
            });
            $('.loading').hide();
            $('.select2').select2();
        }
    </script>
@endsection
