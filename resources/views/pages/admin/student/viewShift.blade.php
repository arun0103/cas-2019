@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/timepicker/bootstrap-timepicker.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="loading">Loading&#8230;</div>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Institutional Shifts
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="institutionShiftTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Shift ID</th>
                    <th>Shift Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Weekly Offs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="institution-shifts-list" name="institution-shifts-list">
                @foreach($shifts as $shift)
                    <tr id="shift{{$shift->id}}">
                        <td>{{$shift->shift_id}}</td>
                        <td>{{$shift->name}}</td>
                        <td>{{$shift->start_time}}</td>
                        <td>{{$shift->end_time}}</td>
                        <td>{{$shift->weekly_off}}</td>
                        <td>
                            <button class="btn btn-warning open_modal" value="{{$shift->id}}"><i class="fa fa-edit"> </i></button>
                            <button class="btn btn-danger delete-row" value="{{$shift->id}}"><i class="fa fa-trash"> </i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Shift ID</th>
                    <th>Shift Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Weekly Offs</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="modal-add">
        <form id="form_addInstitutionShift" class="form-horizontal" method="post" action="/addInstitutionShift">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Institutional Shift</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <input type="hidden" id="inputInstitutionId" disabled value="{{Session::get('company_id')}}" name="institution_id" autocomplete="off">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputShiftId" class="control-label">Shift ID</label>
                                    <input type="text" class="form-control" id="inputShiftId" placeholder="Shift ID" name="shift_id" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="time_start">Start Time</label>
                                <div class="bootstrap-timepicker">
                                    <div class="input-group">
                                        <div class="input-group-addon left-addon">
                                            <input type="text" class="form-control timepicker pull-right" id="time_start" placeholder="Start Time" name="start_time" autocomplete="off">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="time_end">End Time</label>
                                <div class="bootstrap-timepicker">
                                    <div class="input-group">
                                        <div class="input-group-addon left-addon">
                                            <input type="text" class="form-control timepicker pull-right" id="time_end" placeholder="End Time" name="end_time" autocomplete="off">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="select_weeklyOff" class="control-label">Weekly Off on </label>
                                    <select id="select_weeklyOff" class="form-control select2" multiple data-placeholder="Select Off Day(s)" value="selectedWeeklyOff[]" autocomplete="off">
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
    <script src="{{asset('js/plugins/timepicker/bootstrap-timepicker.js')}}"></script>
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        var table;

        $(document).ready(function () {
            table = $('#institutionShiftTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                'fixedHeader' : true
            });

            // table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            //     var data_orig = this.data();
            //     var data = data_orig.split(",");
            //     var dataToDisplay = '';
            //     for(var i=0;i<data.length; i++){
            //         if(i>0) dataToDisplay+= ', ';
            //         switch(data[i]){
            //             case 0: dataToDisplay+="Sunday";break
            //             case 1: dataToDisplay+="Monday";break
            //             case 2: dataToDisplay+="Tuesday";break
            //             case 3: dataToDisplay+="Wednesday";break
            //             case 4: dataToDisplay+="Thursday";break
            //             case 5: dataToDisplay+="Friday";break
            //             case 6: dataToDisplay+="Saturday";break
            //         }
            //     }
                
            //     data[2] = dataToDisplay;
            //     this.data(data);
            //     } )


            
            
            
            $('.select2').select2();

            $('.timepicker').timepicker({
                showInputs: false,
                minuteStep: 1
            });
            
            $('.loading').hide();
            
        });
        

        //Opening Add Modal
        $('#btn_add').click(function(){
            state="add";

            $('#inputShiftId').prop('disabled',false);
            $('#select_weeklyOff').val(null).change();
            $('#error_msg_id').removeClass('error').addClass('no-error');
            $('#form_addInstitutionShift').trigger("reset");
            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Add Institution Shift');
            $('#modal-add').modal('show');    
        });
        var original_shift_id;
        var state;
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var shift_id = $(this).val();
            $('#inputShiftId').prop('disabled',true);
            $.get('/getInstitutionShiftById/' + shift_id, function (data) {
                //success data
                original_shift_id = shift_id;
                console.log(data);
                var weekly_offs = data.weekly_off.split(',');
                $('#inputShiftId').val(data.shift_id);
                $('#inputName').val(data.name);
                $('#time_start').val(data.start_time);
                $('#time_end').val(data.end_time);
                $('#select_weeklyOff').val(weekly_offs).change();
                
                $('#btn_confirm').val("update");
                $('#btn_confirm').text("Update");
                $('#modal-title').text('Edit Institution Shift');
                $('#modal-add').modal('show');
            }) 
        });

        //delete grade and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete an Institution Shift. Are you sure?')){
                var id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteInstitutionShift/' + id,
                    success: function (data) {
                        $("#shift" + id).remove();
                        //table.draw(false);
                    },
                    error: function (data) {
                        console.error('Error:', data.responseJSON);
                    }
                });
            }
        });
        //create new product / update existing product
        $("#btn_confirm").click(function (e) {
            var type = "POST"; //for creating new resource
            var shift_id = $('#inputShiftId').val();
            var url = '/addInstitutionShift'; // by default add 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var weekly_off_array = $('#select_weeklyOff').val();
            var weekly_off_string = '';
            for(var i =0; i < weekly_off_array.length; i++){
                if(i>0)
                    weekly_off_string +=',';
                weekly_off_string += weekly_off_array[i];
                
            }
            
            var formData = {
                institution_id      : $('#inputInstitutionId').val(),
                shift_id            : $('#inputShiftId').val(),
                name                : $('#inputName').val(), 
                start_time          : $('#time_start').val(),
                end_time            : $('#time_end').val(),
                weekly_off          : weekly_off_string               
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addInstitutionShift';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateInstitutionShift/' + original_shift_id;
            }
            console.log(formData);
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    //console.log(data);
                    var weeklyOffs = data.weekly_off.split(',');
                    var weeklyOffs_name ='';
                    for(var i =0 ; i<weeklyOffs.length; i++){
                        if(i>0) weeklyOffs_name +=', ';
                        weeklyOffs_name = weeklyOffs[i];
                    }
                    var shift = '<tr id="shift'+data.id +'"><td>' + data.shift_id + '</td><td>'
                                                                    + data.name + '</td><td>'
                                                                    + data.start_time + '</td><td>'
                                                                    + data.end_time + '</td><td>'
                                                                    + weeklyOffs_name + '</td>';
                    shift += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '"><i class="fa fa-edit"> </i></button>';
                    shift += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.id + '"><i class="fa fa-trash"> </i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#institution-shifts-list').prepend(shift);
                    }else{ //if user updated an existing record
                        $("#shift" + original_shift_id).replaceWith( shift );
                    }
                    $('#form_addInstitutionShift').trigger("reset");
                    $('#modal-add').modal('hide');
                    //table.draw();
                },
                error: function (data) {
                    alert('Error: '+JSON.stringify(data['responseJSON']));
                    console.log('Error:', data);
                }
            });
        });
    </script>
@endsection