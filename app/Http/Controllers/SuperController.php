<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use App\User;
use Response;
use Artisan;
use Session;

class SuperController extends Controller
{
    public function addCompany(Request $req){
        $added_by = Session::get('user_id');
        $validatedData = $req->validate([
            'company_id' => 'required|unique:companies|max:255',
            'company_name' => 'required',
            'company_type' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'street_address_1' => 'required',
            'street_address_2' => 'required',
            'postal_code' => 'required',
            'website' => 'required',
            'contact' => 'required',
            // 'VAT_number' => 'required',
            // 'PAN_number' => 'required',
            // 'registration_number' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'adminName' => 'required',
            'email' => 'required|unique:users',
        ]);
        $admin = new User();
        $admin->name = $req->input('adminName');
        $admin->email = $req->input('email');
        $admin->role = "admin";
        $admin->company_id = $req->input('company_id');
        $admin->password = bcrypt('admin');
        $admin->added_by = $added_by;
        if($admin->save()){
            
            $company = new Company([
                'company_id'=>$req->input('company_id'),
                'company_type'=>$req->input('company_type'),
                'name'=>$req->input('company_name'),
                'country'=>$req->input('country'),
                'state'=>$req->input('state'),
                'city'=>$req->input('city'),
                'street_address_1'=>$req->input('street_address_1'),
                'street_address_2'=>$req->input('street_address_2'),
                'postal_code'=>$req->input('postal_code'),
                'website'=>$req->input('website'),
                'contact'=>$req->input('contact'),
                'VAT_number'=>$req->input('VAT_number'),
                'PAN_number'=>$req->input('PAN_number'),
                'registration_number'=>$req->input('registration_number'),
                'lat'=>$req->input('latitude'),
                'lng'=>$req->input('longitude'),
                'added_by'=>$added_by,
            ]);
            //$dbName = "cas_".$req->input('company_id');
            if($company->save()){
                // if(\DB::statement('create database ' .$dbName )== true){
                    //     Artisan::call('migrate',
                    //     [
                    //         '--database' => 'tenants',
                    //         '--path'     => 'database/tenant/users',
                    //         '--step'     => true,
                    //         '--force'    => true
                    //     ]);
                // }
                return redirect('/super/companies/add')->with('status', 'Company created!');   
            }
        }
        return redirect('/super/companies/add')->with('status', 'Failed to create company!');
    }

    public function getCompanies(){
        $loggedInUserRole = Session::get('role');
        if($loggedInUserRole == "super"){
            $companies = Company::all();
            return view('pages/super/company/viewCompanies',['companies'=>$companies]);
        }else{
            return view('pages/unauthorized');
        }
    }
}
