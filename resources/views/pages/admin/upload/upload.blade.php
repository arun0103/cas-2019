@extends('layouts.master')

@section('content')

<div class="container">
    @if (session('message'))
        <div class="alert alert-success">
            Total : {{ session('message.total') }}<br>
            Added : {{ session('message.added') }}<br>
            Rejected : {{ session('message.rejected') }}<br>
        </div>
    @endif
<h3> Upload Raw File Generated from device </h3></br>
    <div class="row">
        <form action="/uploadFile" method="post" enctype="multipart/form-data">
            @csrf
            <div >
                <input type="file" class="form-control-file" name="fileToUpload" id="inputEmployeePhoto" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">Please upload a valid file.</small>
            </div>
            <button type="submit" id="btn_submit">Upload</button>
            
        </form>
    </div>
</div>
@endsection
