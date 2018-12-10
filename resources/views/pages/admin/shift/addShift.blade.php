@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{asset('js/plugins/timepicker/bootstrap-timepicker.css')}}">
@endsection

@section('content')
<div class="row roundPadding20" id="addNewShift"> 
    <div class="col-sm-12">
        <form class="form-horizontal" method="post" action="/addShift">
        {{csrf_field()}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputShiftId" class="col-sm-4 control-label">Shift ID</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputShiftId" placeholder="Shift ID" name="shift_id">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputName" placeholder="Name" name="shift_name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                <div class="bootstrap-timepicker">
                    <div class="form-group">
                        <label for="timePicker_start" class="col-sm-4 control-label">Start Time</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control timepicker" id="timePicker_start" name="start_time">

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
                </div>
                <div class="col-sm-6">
                    <div class="bootstrap-timepicker">
                    <div class="form-group">
                        <label for="timePicker_end" class="col-sm-2 control-label">End Time</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control timepicker" id="timePicker_end" name="end_time">

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputGraceEarly" class="col-sm-2 control-label">Early Grage</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputGraceEarly" placeholder="Early Grace (in minutes)" name="early_grace">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputGraceLate" class="col-sm-2 control-label">Late Grage</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputGraceLate" placeholder="Late Grace (in minutes)" name="late_grace">
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div> 
        </form>
    </div>
</div>

@endsection

@section('footer')
<script src="{{asset('js/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script>
$(function () {
//Timepicker
$('#timePicker_start').timepicker({
      showInputs: false
    });
$('#timePicker_end').timepicker({
      showInputs: false
    });
});
</script>
@endsection