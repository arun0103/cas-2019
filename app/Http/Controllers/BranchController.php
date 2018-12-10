<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use Carbon\Carbon;
use Session;

class BranchController extends Controller
{
    public function addBranch(Request $request){
        
        $branch = Branch::create($request->input());
        return response()->json($branch);
        // $branch = new Branch([
        //     'branch_id'=>request('branch_id'),
        //     'name'=>request('branch_name'), 
        //     'website'=>request('website'),
        //     'contact'=>request('contact'),
        //     'country'=>request('country'), 
        //     'state'=>request('state'),
        //     'city'=>request('city'),
        //     'street_address_1'=>request('street_address_1'),
        //     'street_address_2'=>request('street_address_2'),
        //     'postal_code'=>request('postalCode'),
        //     'lat'=>request('latitude'),
        //     'lng'=>request('longitude'),
        //     'VAT_number'=>request('VAT_number'),
        //     'PAN_number'=>request('PAN_number'),
        //     'registration_number'=>request('registration_number'),
        // ]);
        // if($branch->save()){
        //     return redirect('/admin/branch/add')->with('status', 'Branch created!');
        // }
    }
    public function getBranches(){
        $company_id = Session::get('company_id');
        $branches = Branch::where('company_id',$company_id)->get();
        return view('pages/admin/branch/viewBranches',['branches'=>$branches]);
    }
    public function getBranchById($id){
        $branch = Branch::where('branch_id',$id)->get();
        return $branch[0];
    }
    public function deleteBranch($id){
        $branch = Branch::where('branch_id',$id)->delete();
        return response()->json($branch);
    }
    public function updateBranch(Request $request, $id){
        $branch = Branch::where('branch_id',$id)->first();
        $branch->branch_id = $request->branch_id;
        $branch->name = $request->name;
        $branch->country = $request->country;
        $branch->state = $request->state;
        $branch->city = $request->city;
        $branch->street_address_1 = $request->street_address_1;
        $branch->street_address_2 = $request->street_address_2;
        $branch->postal_code = $request->postal_code;
        $branch->website = $request->website;
        $branch->contact = $request->contact;
        $branch->VAT_number = $request->VAT_number;
        $branch->PAN_number = $request->PAN_number;
        $branch->registration_number = $request->registration_number;
        $branch->lat = $request->lat;
        $branch->lng = $request->lng;
        $branch->updated_at = Carbon::now();
        
        $branch->save();
        return response()->json($branch);
    }
}
