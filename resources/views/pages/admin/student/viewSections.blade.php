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
            <h3 class="box-title">List Of Sections
                <button type="button" id="btn_add" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">Add New</button>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="sectionTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Section ID</th>
                    <th>Section Name</th>
                    <th>Grade Name</th>
                    <th># Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sections-list" name="sections-list">
                @foreach($sections as $section)
                    <tr id="section{{$section->section_id}}">
                        <td>{{$section->section_id}}</td>
                        <td>{{$section->name}}</td>
                        <td>{{$section->grade['name']}}</td>
                        <td>{{$section->students->count()}}</td>
                        <td>
                            <button class="btn btn-warning open_modal" value="{{$section->section_id}}"><i class="fa fa-edit"> </i></button>
                            <button class="btn btn-danger delete-row" value="{{$section->section_id}}"><i class="fa fa-trash"> </i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Section ID</th>
                    <th>Section Name</th>
                    <th>Grade Name</th>
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
        <form id="form_addSection" class="form-horizontal" method="post" action="/addSection">
            {{ csrf_field() }}
            <div class="modal-dialog modal-lg" style="width:90% !important;height:90% !important; padding:0;margin:0 auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Add Section</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="inputInstitutionId" disabled value="{{Session::get('company_id')}}" name="institution_id">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="inputSectionId" class="control-label">Section ID</label>
                                    <input type="text" class="form-control" id="inputSectionId" placeholder="Section ID" name="section_id">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="select_grade" class="control-label">Grade</label>
                                    <select id="select_grade" class="form-control select2" data-placeholder="Select Grade" name="selectedGrade">
                                        <option></option>    
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->grade_id}}">{{$grade->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
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
            $('#sectionTable').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true
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
            $('#modal-title').text('Add Section');
            $('#modal-add').modal('show');    
        });
        var original_section_id;
        var state;
        //Opening Edit Modal
        $(document).on('click', '.open_modal', function(){
            state="update";
            $('#error_msg_id').removeClass('error').addClass('no-error');
            var section_id = $(this).val();
            $.get('/getSectionById/' + section_id, function (data) {
                //success data
                original_section_id = section_id;
                console.log(data);
                $('#inputSectionId').val(data.section_id);
                if(data.grade != null) // in case the grade is deleted
                    $('#select_grade').val(data.grade.grade_id).trigger("change");
                $('#inputName').val(data.name);
                
                $('#btn_confirm').val("update");
                $('#btn_confirm').text("Update");
                $('#modal-title').text('Edit Section');
                $('#modal-add').modal('show');
            }) 
        });

        //delete shift and remove it from list
        $(document).on('click','.delete-row',function(){
            if(confirm('You are about to delete a Section. Are you sure?')){
                var section_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: '/deleteSection/' + section_id,
                    success: function (data) {
                        $("#section" + section_id).remove();
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
            var url = '/addSection'; // by default add section
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            e.preventDefault(); 
            var formData = {
                institution_id      : $('#inputInstitutionId').val(),
                grade_id            : $('#select_grade').val(),
                section_id          : $('#inputSectionId').val(),
                name                : $('#inputName').val(),                
            }
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn_confirm').val();
            if(state=="add"){
                type = "POST"; 
                url = '/addSection';
            }else if (state == "update"){
                type = "PUT"; //for updating existing resource
                url = '/updateSection/' + original_section_id;
            }
            console.log(formData);
            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    //console.log(data);
                    var section = '<tr id="section'+data.section_id +'"><td>' + data.section_id + '</td><td>'
                                                                        + data.name + '</td><td>' 
                                                                        + data.grade['name'] + '</td>';
                    section += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.section_id + '"><i class="fa fa-edit"> </i></button>';
                    section += ' <button class="btn btn-danger btn-delete delete-row" value="' + data.section_id + '"><i class="fa fa-trash"> </i></button></td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#sections-list').prepend(section);
                    }else{ //if user updated an existing record
                        $("#section" + original_section_id).replaceWith( section );
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