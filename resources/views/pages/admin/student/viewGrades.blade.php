@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="loading">Loading&#8230;</div>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">List Of Grades
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="gradeTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Grade ID</th>
                    <th>Grade Name</th>
                    <th># Sections</th>
                    <th># Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="grades-list" name="grades-list">
                @foreach($grades as $grade)
                    <tr id="grade{{$grade->grade_id}}">
                        <td>{{$grade->grade_id}}</td>
                        <td>{{$grade->name}}</td>
                        <td>{{$grade->sections->count()}}</td>
                        <td>{{$grade->students->count()}}</td>
                        <td>
                            <button class="btn btn-warning open_modal" value="{{$grade->grade_id}}"><i class="fa fa-edit"> </i></button>
                            <button class="btn btn-danger delete-row" value="{{$grade->grade_id}}"><i class="fa fa-trash"> </i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Grade ID</th>
                    <th>Grade Name</th>
                    <th># Sections</th>
                    <th># Students</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="modal-add">
        <form id="form_addGrade" class="form-horizontal" method="post" action="/addGrade">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Grade</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <input type="hidden" id="inputInstitutionId" disabled value="{{Session::get('company_id')}}" name="institution_id">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputGradeId" class="control-label">Grade ID</label>
                                    <input type="text" class="form-control" id="inputGradeId" placeholder="Grade ID" name="grade_id" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Name" name="name">
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
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#gradeTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                fixedHeader : true
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
            $('#form_addGrade').trigger("reset");
            $('#btn_confirm').val("add");
            $('#btn_confirm').text("Add");
            $('#modal-title').text('Add Grade');
            $('#modal-add').modal('show');    
        });
        var original_grade_id;
        var state;
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var grade_id = $(this).val();
            $.get('/getGradeById/' + grade_id, function (data) {
                //success data
                original_grade_id = grade_id;
                console.log(data);
                $('#inputGradeId').val(data.grade_id);
                $('#inputName').val(data.name);
                
                $('#btn_confirm').val("update");
                $('#btn_confirm').text("Update");
                $('#modal-title').text('Edit Grade');
                $('#modal-add').modal('show');
            }) 
        });

        //delete grade and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete a Grade. Are you sure?')){
                var grade_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteGrade/' + grade_id,
                    success: function (data) {
                        $("#grade" + grade_id).remove();
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
            var shift_id = $('#inputGradeId').val();
            var url = '/addGrade'; // by default add grade
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                institution_id      : $('#inputInstitutionId').val(),
                grade_id            : $('#inputGradeId').val(),
                name                : $('#inputName').val(),                
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addGrade';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateGrade/' + original_grade_id;
            }
            console.log(formData);
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    //console.log(data);
                    var grade = '<tr id="grade'+data.grade_id +'"><td>' + data.grade_id + '</td><td>'
                                                                        + data.name + '</td>';
                    grade += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.grade_id + '"><i class="fa fa-edit"> </i></button>';
                    grade += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.grade_id + '"><i class="fa fa-trash"> </i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#grades-list').prepend(grade);
                    }else{ //if user updated an existing record
                        $("#grade" + original_grade_id).replaceWith( grade );
                    }
                    $('#form_addGrade').trigger("reset");
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