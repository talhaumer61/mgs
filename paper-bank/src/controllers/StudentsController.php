<?php

// use src\model\Student;

use src\model\StudentModel;

class Students extends Controller
{
    protected $route = 'students';

    public function __construct()
    {
        $this->authMiddleware();
    }

    public function index()
    {
        $students = StudentModel::all();
        $classes = ClassesModel::all();

        $data = [
            'students' => $students,
            'classes' => $classes
        ];

        $this->view("students/index", $data);
    }

    public function add()
    {
        $classes = ClassesModel::all();
        $sections = SectionModel::all();

        $data = [
            "classes" => $classes,
            "sections" => $sections
        ];

        $this->view("students/create", $data);
    }

    public function create()
    {
        $body = Request::body();

        $studentModel = new StudentModel();


        $isSaved = $studentModel->saveData([
            'student_name' => $body['std_name'],
            'guardian_name' => $body['std_fathername'],
            'gender' => $body['std_gender'] == 'male' ? true : false,
            'guardian_cnic' => $body['std_nic'],
            'phone' => $body['std_phone'],
            'dob' => date('Y-m-d', strtotime($body['std_dob'])),
            'section_id' => $body['id_section'],
            'roll_num' => $body['std_rollno'],
            'city' => $body['std_city'],
            'admission_date' => date('Y-m-d', strtotime($body['std_admissiondate'])),
            'form_no' => $body['form_no'],
            'address' => $body['std_address'],
            'class_id' => $body['id_class'],
            'status' => $body['std_status'] ?? true
        ]);

        if (!$isSaved)
            throw new AppError($this->route, "Error creating the record, Please make sure you are providing valid informatiion", 400);

        return redirect($this->route, [
            'type' => 'success',
            'message' => "Record inserted successfully"
        ]);
    }

    public function edit($id)
    {
        $student = StudentModel::where('student_id', $id)->first();

        if (!$student)
            throw new AppError($this->route, "No record found for the givin Id", 404);

        $section = $student->section;
        $class = $student->class;
        $classes = ClassesModel::all();
        $sections = $class->sections;

        $data = [
            'student' => $student,
            'section' => $section,
            'class' => $class,
            'classes' => $classes,
            'sections' => $sections,
            'id' => $id
        ];

        $this->view('students/edit', $data);
    }

    public function delete($id)
    {
        StudentModel::where('student_id', $id)->delete();
        return redirect("students/index", [
            'type' => 'success',
            'message' => 'Record Deleted successfully'
        ]);
    }

    public function update($id)
    {
        $body = Request::body();

        $studentModel = new StudentModel();

        //        return dump($body);

        $student = $studentModel->updateData($id, [
            'student_name' => $body['std_name'],
            'guardian_name' => $body['std_fathername'],
            'gender' => $body['std_gender'] == 'male' ? true : false,
            'guardian_cnic' => $body['std_nic'],
            'phone' => $body['std_phone'],
            'dob' => date('Y-m-d', strtotime($body['std_dob'])),
            'section_id' => $body['id_section'],
            'roll_num' => $body['std_rollno'],
            'city' => $body['std_city'],
            'admission_date' => date('Y-m-d', strtotime($body['std_admissiondate'])),
            'address' => $body['std_address'],
            'class_id' => $body['id_class'],
            'status' => $body['std_status']
        ]);

        if (!$student)
            throw new AppError($this->route, "Invalid student Id", 400);

        return redirect($this->route, ['type' => 'success', 'message' => "Record updated successfully"]);
    }

    // Get all the students of a particular class
    public function get_students_by_class()
    {

        if (!Request::body('id_class')) {
            throw new AppError($this->route, "Please provide a valid class name", 400);
        }

        $class_id = Request::body('id_class');
        $students = StudentModel::where('class_id', $class_id)->get();
        $classes = ClassesModel::all();

        $data = [
            'students' => $students,
            'classes' => $classes
        ];

        $this->view("students/index", $data);
    }
}
