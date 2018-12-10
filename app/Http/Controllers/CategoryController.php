<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Session;

class CategoryController extends Controller
{
    public function getCategories(){
        $comp_id = (String)Session::get('company_id');
        $categories = Category::where('company_id',$comp_id)->get();
        return view('pages/admin/category/viewCategories',['categories'=>$categories]);
    }
    public function getCategoriesById($id){
        $categories = Category::where('category_id',$id)->first();
        return $categories;
    }
    public function addCategory(Request $request){
        $newCategory = Category::create($request->input());
        return response()->json($newCategory);
        // $category = new Category([
        //     'category_id'=>request('category_id'),
        //     'name'=>request('category_name'),
        //     'max_late_allowed'=>request('maxLateAllowed'),
        //     'max_early_allowed'=>request('maxEarlyAllowed'),
        //     'max_short_leave_allowed'=>request('maxShortLeaveAllowed'),
        //     'min_working_days_weekly_off'=>request('minWorkingDaysWeeklyOff'),
        //     'weekly_off_cover'=>request('weeklyOffCover'),
        //     'paid_holiday_cover'=>request('paidHolidayCover'),
        // ]);
        // if($category->save()){
        //     return redirect('/admin/category/add')->with('status',"Category created!");
        // }
    }
    public function updateCategory(Request $request, $id){
        $update = Category::where('category_id',$id)->first();
        $update->category_id = $request->category_id;
        $update->name = $request->name;
        $update->max_late_allowed = $request->max_late_allowed;
        $update->max_early_allowed = $request->max_early_allowed;
        $update->max_short_leave_allowed = $request->max_short_leave_allowed;
        $update->min_working_days_weekly_off = $request->min_working_days_weekly_off;
        $update->weekly_off_cover = $request->weekly_off_cover;
        $update->paid_holiday_cover = $request->paid_holiday_cover;
        $update->save();
        return response()->json($update);
    }
    public function deleteCategory($id){
        $deleted = Category::where('category_id',$id)->delete();
        return response()->json($deleted);
    }
}
