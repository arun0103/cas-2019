@extends('layouts.master')

@section('content')
@if (session('status'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        {{ session('status') }}
    </div>
@endif
<div class="row roundPadding20" id="addNewHoliday"> 
    <div class="col-sm-12">
        <form class="form-horizontal" method="post" action="/addHoliday">
        {{csrf_field()}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="inputHolidayName" class="col-sm-4 control-label">Holiday Name</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="inputHolidayName" placeholder="Holiday Description" name="holiday_description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div><label for="datepicker_holidayDate" class="col-sm-2 control-label">Date</label></div>
                        <div class="col-sm-10">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker_holidayDate" name="holiday_date">
                            </div>
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
<script type="text/javascript">
$(function () {
    $('#datepicker_holidayDate').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 0,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        rtl: true,
        orientation: "auto"
    });
});
</script>
@endsection