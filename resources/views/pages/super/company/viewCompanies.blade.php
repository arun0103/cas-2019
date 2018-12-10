@extends('layouts.master')

@section('head')
<link rel="stylesheet" href="{{asset('js/plugins/datatables/css/dataTables.bootstrap4.css')}}">

 
@endsection


@section('content')

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Of Companies</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="employeeTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Company ID</th>
                <th>Company Name</th>
                <th>Address</th>
                <th>Website</th>
                <th>Contact</th>
                <th>VAT Number</th>
                <th>PAN Number</th>
                <th>Registration Number</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
            <tr>
                <th>{{$company->company_id}}</th>
                <th>{{$company->name}}</th>
                <th>{{$company->street_address_1}}, {{$company->street_address_2}}, {{$company->city}}, {{$company->state}}, {{$company->country}} - {{$company->postal_code}}</th>
                <th>{{$company->website}}</th>
                <th>{{$company->contact}}</th>
                <th>{{$company->VAT_number}}</th>
                <th>{{$company->PAN_number}}</th>
                <th>{{$company->registration_number}}</th>
                <th>{{$company->lat}}</th>
                <th>{{$company->lng}}</th>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Company ID</th>
                <th>Company Name</th>
                <th>Address</th>
                <th>Website</th>
                <th>Contact</th>
                <th>VAT Number</th>
                <th>PAN Number</th>
                <th>Registration Number</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
        </tfoot>
        </table>
    </div>
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->

@endsection

@section('footer')
<script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.js')}}"></script>
<script>
  $(function () {

    $('#employeeTable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      'scrollX'     : true
    })
  })
</script>
@endsection