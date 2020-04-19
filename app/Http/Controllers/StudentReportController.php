<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use PDF;
use PdfReport;
use ExcelReport;
use Carbon\Carbon;

use App\Student;
use App\Student_Grade;
use App\Student_Section;
use App\Student_Roster;
use App\Student_Punch;


class StudentReportController extends Controller
{
    public function getSectionsOfGrade($grade_id){
        $company_id = Session::get('company_id');
        //dd($company_id);
        $sections = Student_Section::where([['institution_id',$company_id],['grade_id',$grade_id]])->get(['section_id','name']);
        return response()->json($sections);
    }
}
