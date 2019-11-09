<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Carbon\Carbon;

use App\User;
use App\Student;
use App\Student_Shift;
use App\Student_Grade;
use App\Student_Section;

class StudentController extends Controller
{
    public function getStudents(){
        $institution_id = Session::get('company_id');
        $students = Student::where('institution_id',$institution_id)->with('grade','section')->get();
        $grades = Student_Grade::where('institution_id',$institution_id)->get();
        $shifts = Student_Shift::where('institution_id',$institution_id)->get();
        
        return view('pages/admin/student/viewStudents',['students'=>$students, 'grades'=>$grades, 'shifts'=>$shifts]);
    }
    public function getStudentById($id){
        $find = Student::where('id',$id)->with('grade','section')->first();
        return $find;
    }
    public function addStudent(Request $req){
        $new = Student::create($req->input());
        $dataToSend = Student::where('id',$new->id)->with('grade','section')->first();

        if($req->email != null){
            $user = User::create([
                'company_id'    => $new->institution_id,
                'employee_id'   => $req->student_id,
                'name'          => $new->name,
                'email'         => $new->email,
                'role'          => 'student',
                'password'      => bcrypt('test@123'),
                'added_by'      => Session::get('user_id'),
                'password_changed'=> 0
            ]);
            $user->save();
        }
        return response()->json($dataToSend);
    }
    public function deleteStudent($id){
        $institution_id = Session::get('company_id');
        $delete = Student::where('id',$id)->delete();
        return response()->json($delete);
    }
    public function updateStudent(Request $req, $id){
        $update = Student::where('id',$id)->first();

        $check_user_exists = User::where('employee_id',$update->student_id)->first();

    
        $update->student_id = $req->student_id;
        $update->name = $req->name ;
        $update->shift_id = $req->shift_id ;
        $update->grade_id = $req->grade_id ;
        $update->section_id = $req->section_id ;
        $update->card_number = $req->card_number ;
        $update->dob = $req->dob ;
        $update->gender = $req->gender ;
        $update->permanent_address = $req->permanent_address ;
        $update->temporary_address = $req->temporary_address ;
        $update->email = $req->email ;
        $update->father_name = $req->father_name ;
        $update->mother_name = $req->mother_name ;
        $update->guardian_name = $req->guardian_name ;
        $update->guardian_relation = $req->guardian_relation ;
        $update->contact_1_number = $req->contact_1_number ;
        $update->contact_2_number = $req->contact_2_number ;
        $update->contact_1_name = $req->contact_1_name ;
        $update->contact_2_name = $req->contact_2_name ;
        $update->sms_option = $req->sms_option ;
        $update->updated_at = Carbon::now();

        $update->save();
        if($check_user_exists != null){ // User Exists .. update
            if($check_user_exists->email != $update->email){
                $check_user_exists->email = $update->email;
                $check_user_exists->save();
            }
        }else {
            $user = User::create([
                'company_id'    => Session::get('company_id'),
                'employee_id'   => $update->student_id,
                'name'          => $update->name,
                'email'         => $update->email,
                'role'          => 'student',
                'password'      => bcrypt('test@123'),
                'added_by'      => Session::get('user_id'),
                'password_changed'=> 0
            ]);
            $user->save();
        }
        
        return response()->json($update);
        
    }
    

    public function getGrades(){
        $institution_id = Session::get('company_id');
        $grades = Student_Grade::where('institution_id',$institution_id)->with(['students'=>function($query){
            $query->count();
        }])->with(['sections'=>function($query2){
            $query2->count();
        }])->get();

        return view('pages/admin/student/viewGrades',['grades'=>$grades]);
    }

    public function getGradeById($grade_id){
        $institution_id = Session::get('company_id');
        $grade = Student_Grade::where([['institution_id',$institution_id],['grade_id',$grade_id]])->first();
    
        return $grade;
    }

    public function addGrade(Request $req){
        $new = Student_Grade::create($req->input());
        return response()->json($new);
    }
    public function updateGrade(Request $req, $id){
        $institution_id = Session::get('company_id');
        $toUpdate = Student_Grade::where([['institution_id',$institution_id],['grade_id',$id]])->first();
        $toUpdate->grade_id = $req->grade_id;
        $toUpdate->name = $req->name;
        $toUpdate->updated_at = Carbon::now();
        $toUpdate->save();
        return response()->json($toUpdate);
    }
    public function deleteGrade($id){
        $institution_id = Session::get('company_id');
        $delete = Student_Grade::where([['institution_id',$institution_id],['grade_id',$id]])->delete();
        return response()->json($delete);
    }
    /************************************ Section *************************************************/
    public function getSections(){
        $institution_id = Session::get('company_id');
        $sections = Student_Section::where('institution_id',$institution_id)->with('grade')->get();
        $grades = Student_Grade::where('institution_id',$institution_id)->with(['students'=>function($query){
            $query->count();
        }])->get();

        return view('pages/admin/student/viewSections',['sections'=>$sections, 'grades'=>$grades]);
    }
    public function getSectionById($id){
        $institution_id = Session::get('company_id');
        $section = Student_Section::where([['institution_id',$institution_id],['section_id',$id]])->with('grade')->first();
        return $section;
    }
    public function addSection(Request $req){
        $new = Student_Section::create($req->input());
        $dataToSend = Student_Section::where('id',$new->id)->with('grade')->first();
        return response()->json($dataToSend);
    }
    public function updateSection(Request $req, $id){
        $institution_id = Session::get('company_id');
        $toUpdate = Student_Section::where([['institution_id',$institution_id],['section_id',$id]])->first();
        $toUpdate->grade_id = $req->grade_id;
        $toUpdate->section_id = $req->section_id;
        $toUpdate->name = $req->name;
        $toUpdate->updated_at = Carbon::now();
        $toUpdate->save();
        $dataToSend = Student_Section::where('id',$toUpdate->id)->with('grade')->first();
        return response()->json($dataToSend);
    }
    public function deleteSection($id){
        $institution_id = Session::get('company_id');
        $delete = Student_Section::where([['institution_id',$institution_id],['section_id',$id]])->delete();
        return response()->json($delete);
    }
    public function getSectionsOfGrade($grade_id){
        $institution_id = Session::get('company_id');
        $sections = Student_Section::where([['institution_id',$institution_id],['grade_id',$grade_id]])->get();
        return $sections;
    }
    /**********************************************************************************************************/

    public function getStudentsOfGrade($grade_id){
        $institution_id = Session::get('company_id');
        $studentsOfGrade = Student::where([['institution_id',$institution_id],['grade_id',$grade_id]])->get();
        return $studentsOfGrade;
    }
}
