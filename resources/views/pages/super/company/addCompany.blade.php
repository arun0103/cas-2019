@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="{{asset('js/plugins/select2/select2.min.css')}}">
@endsection
@section('content')
@if (session('status'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        {{ session('status') }}
    </div>
@endif
<h3>Add Company</h3>
<div class="row" id="addNewCompany"> 
    <div class="col-md-12">
        <form id="form_addCompany" class="form-horizontal" method="post" action="/addCompany" enctype="multipart/form-data" autocomplete="off">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputCompanyId" class="control-label">Company ID <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputCompanyId" placeholder="Company ID" name="company_id" value="{{old('company_id')}}" autocomplete="false" >
                        @if($errors->has('company_id'))
                            <p class="alert alert-danger">{{ $errors->first('company_id') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="select_company_type" class="control-label">Company Type <span class="required">*</span></label>
                        <select id = "select_company_type" class="form-control select2" data-placeholder="Company Type" name="company_type">
                            <option></option>
                            <option value="business">Business</option>
                            <option value="institute">Institution</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputName" placeholder="Name" name="company_name" value="{{old('company_name')}}">
                        @if($errors->has('company_name'))
                            <p class="alert alert-danger">{{ $errors->first('company_name') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputWebsite" class="control-label">Website <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputWebsite" placeholder="Website" name="website" value="{{old('website')}}">
                        @if($errors->has('website'))
                            <p class="alert alert-danger">{{ $errors->first('website') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputContactNumber" class="control-label">Contact Number <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputContactNumber" placeholder="Contact Number" name="contact" value="{{old('contact')}}">
                        @if($errors->has('contact'))
                            <p class="alert alert-danger">{{ $errors->first('contact') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputCountry" class="control-label">Country <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputCountry" placeholder="Country" name="country" value="{{old('country')}}">
                        @if($errors->has('country'))
                            <p class="alert alert-danger">{{ $errors->first('country') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputState" class="control-label">State <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputState" placeholder="State" name="state" value="{{old('state')}}">
                        @if($errors->has('state'))
                            <p class="alert alert-danger">{{ $errors->first('state') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputCity" class="control-label">City <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputCity" placeholder="City" name="city" value="{{old('city')}}">
                        @if($errors->has('city'))
                            <p class="alert alert-danger">{{ $errors->first('city') }}</p>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputStreet_address_1" class="control-label">Street Address 1 <span class="required">*</span></label>
                        <textarea class="form-control" id="inputStreet_address_1" placeholder="Street Address 1" name="street_address_1" value="{{old('street_address_1')}}"></textarea>
                        @if($errors->has('street_address_1'))
                            <p class="alert alert-danger">{{ $errors->first('street_address_1') }}</p>
                        @endif
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputStreet_address_2" class="control-label">Street Address 2 <span class="required">*</span></label>
                        <textarea class="form-control" id="inputStreet_address_2" placeholder="Street Address 2" name="street_address_2" value="{{old('street_address_2')}}"></textarea>
                        @if($errors->has('street_address_2'))
                            <p class="alert alert-danger">{{ $errors->first('street_address_2') }}</p>
                        @endif
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPostalCode" class="control-label">Postal Code <span class="required">*</span></label>
                        <input type="number" class="form-control" id="inputPostalCode" placeholder="Postal Code" name="postal_code" value="{{old('postal_code')}}"></textarea>
                        @if($errors->has('postal_code'))
                            <p class="alert alert-danger">{{ $errors->first('postal_code') }}</p>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputVATNumber" class="control-label">VAT Number</label>
                        <input type="text" class="form-control" id="inputVATNumber" placeholder="VAT Number" name="VAT_number" value="{{old('VAT_number')}}">
                        @if($errors->has('VAT_number'))
                            <p class="alert alert-danger">{{ $errors->first('VAT_number') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPANNumber" class="control-label">PAN Number</label>
                        <input type="text" class="form-control" id="inputPANNumber" placeholder="PAN Number" name="PAN_number" value="{{old('PAN_number')}}">
                        @if($errors->has('PAN_number'))
                            <p class="alert alert-danger">{{ $errors->first('PAN_number') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputRegistrationNumber" class="control-label">Registration Number</label>
                        <input type="text" class="form-control" id="inputRegistrationNumber" placeholder="Registration Number" name="registration_number" value="{{old('registration_number')}}">
                        @if($errors->has('registration_number'))
                            <p class="alert alert-danger">{{ $errors->first('registration_number') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                        <div class="form-group">
                        <label for="inputLatitude" class="control-label">Latitude</label>
                        <input type="text" class="form-control" id="inputLatitude" placeholder="Latitude" name="latitude" value="{{old('latitude')}}">
                        @if($errors->has('latitude'))
                            <p class="alert alert-danger">{{ $errors->first('latitude') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputLongitude" class="control-label">Longitude</label>
                        <input type="text" class="form-control" id="inputLongitude" placeholder="Longitude" name="longitude" value="{{old('longitude')}}">
                        @if($errors->has('longitude'))
                            <p class="alert alert-danger">{{ $errors->first('longitude') }}</p>
                        @endif
                    </div>
                </div>
            </div>            
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputAdminName" class="control-label">Admin Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="inputAdminName" placeholder="Admin Name" name="adminName" value="{{old('adminName')}}">
                        @if($errors->has('adminName'))
                            <p class="alert alert-danger">{{ $errors->first('adminName') }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputEmail" class="control-label">Admin Email <span class="required">*</span></label>
                        <input type="email" class="form-control" id="inputEmail" placeholder="Admin Email" name="email" value="{{old('email')}}">
                        @if($errors->has('email'))
                            <p class="alert alert-danger">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                    <label>
                        <input type="checkbox" id="chk_userAgreement" onchange="userAgreed(this.value)"> I agree to the <a href="#">terms and conditions</a>
                    </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" id="btn_submit" class="btn btn-primary">Submit</button>
            </div> 
        </form>
    </div>
</div>

@endsection

@section('footer')
<script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('js/plugins/jquery/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#btn_submit').prop('disabled', true);
        $('.select2').select2();
        $('#form_addCompany').validate();
    });

    function userAgreed(value){
        if($('#chk_userAgreement').is(":checked")){
            $('#btn_submit').prop('disabled', false); 
        }else{
            $('#btn_submit').prop('disabled', true).change();
        }
    }
</script>

@endsection