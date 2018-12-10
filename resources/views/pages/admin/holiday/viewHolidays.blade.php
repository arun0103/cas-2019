@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{asset('js/plugins/fullcalendar/fullcalendar.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
  <div class="loading">Loading&#8230;</div>
  <div>
      <input type="hidden" id="inputCompanyId" disabled value="{{Session::get('company_id')}}">
  </div>
  <div class="row">
      <div class="col-md-12">
          <div class="box box-primary">
              <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
              </div>
          <!-- /.box-body -->
          </div>
      <!-- /. box -->
      </div>
  </div>
  <div class="modal fade" id="modal-add">
      <form id="form_addHoliday" class="form-horizontal" >
          {{ csrf_field() }}
          
          <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="modal-title">Add Holiday</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-sm-12">
                          <div class="form-group">
                              <label for="inputHolidayName" class="control-label">Holiday Name</label>
                              <textarea class="form-control" id="inputHolidayName" placeholder="Holiday Description" name="holiday_desc" required></textarea>
                              <span id="error_name" class="no-error">Required</span>
                          </div>
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


@endsection

@section('footer')
<!-- <script src="../bower_components/moment/moment.js"></script> -->
<script src="{{asset('js/plugins/fullcalendar/lib/moment.min.js')}}"></script>
<script src="{{asset('js/plugins/fullcalendar/lib/jquery.min.js')}}"></script>
<script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
<script src="{{asset('js/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
<style>

  html, body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 900px;
    margin: 40px auto;
  }

</style>
<script>
 var date_selected;
  function getHolidaysFromDatabase(){
    $.ajax({
      type:'get',
      url:'/getHolidays',
      dateType:'json',
      success: function (data)
      {      
        $('#calendar').fullCalendar('addEventSource',data);
        
      }
    });
  }
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      defaultDate: '2018-03-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      clickable: true
    });
    getHolidaysFromDatabase();
    $('.loading').hide();
  });
    
  $('#calendar').fullCalendar({
    dayClick: function(date, jsEvent, view) {
      date_selected = date.format();
      if (IsDateHasEvent(date)) 
        alert("event exists!!!!");
      else{
        if(confirm("Do you want to Add holiday at "+ date.format())){
          //$(this).css('background-color', 'red');
          $('#form_addHoliday').trigger("reset");
          $('#btn_confirm').val("add");
          $('#btn_confirm').text("Add");
          $('#modal-title').text('Add Holiday @ ' +date.format());
          $('#modal-add').modal('show');   
        }
        else{
          $(this).css('background-color', 'transparent');
        }
      }
      
      
      /*
        // alert('Clicked on: ' + date.format());

        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

        // alert('Current view: ' + view.name);

        // change the day's background color just for fun
        //$(this).css('background-color', 'red');
      */
    },
    eventClick: function(calEvent, jsEvent, view) {
      if(confirm("Do you want to delete this holiday???")){
        alert(calEvent.title + " will be deleted . id = " + calEvent.id);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: 'DELETE',
          url: '/deleteHoliday/'+calEvent.id,
          
          success: function (data) {
            console.log(data);
            $('#calendar').fullCalendar('removeEvents',event.id);
            getHolidaysFromDatabase();
          },
          error: function (data) {
            alert('Error: '+JSON.stringify(data['responseJSON']));
            console.log('Error:', data);
          }
        });
        
      }
      // alert('Event: ' + calEvent.title);
      // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
      // alert('View: ' + view.name);

      // change the border color just for fun
      $(this).css('border-color', 'red');

      }

  });
  //create new product / update existing product
  $("#btn_confirm").click(function (e) {
    if($('#inputHolidayName').val()!=''){
      $('#error_name').removeClass('error').addClass('no-error');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      e.preventDefault(); 
      $.ajax({
        type: 'POST',
        url: '/addHoliday',
        data: {
          holiday_description : $('#inputHolidayName').val(),
          holiday_date        : date_selected,
          branch_id           : $('#inputCompanyId').val(),
          company_id          : $('#inputCompanyId').val()
        },
        dataType: 'json',
        success: function (data) {
          $('#calendar').fullCalendar('renderEvent',data);
          //$('#calendar').fullCalendar('refetchData');
          $('#form_addHoliday').trigger("reset");
          $('#modal-add').modal('hide');
        },
        error: function (data) {
          alert('Error: '+JSON.stringify(data['responseJSON']));
          console.log('Error:', data);
        }
      });
    }else{
      $('#error_name').removeClass('no-error').addClass('error').text('Required!');
    }
  });
  
  // check if this day has an event before
  function IsDateHasEvent(date) {
    var allEvents = [];
    allEvents = $('#calendar').fullCalendar('clientEvents');
    var event = $.grep(allEvents, function (v) {
      return +v.start === +date;
    });
    return event.length > 0;
  }
 

</script>

@endsection