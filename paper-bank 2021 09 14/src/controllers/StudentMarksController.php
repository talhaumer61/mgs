<?php

use Illuminate\Contracts\View\View;
use src\model\StudentModel;

class StudentMarks extends Controller
{
  protected $route = "studentmarks";

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    $classes = ClassesModel::all();
    $paperTerms = PaperType::all();

    $data = [
      'classes' => $classes,
      'paper_types' => $paperTerms,
      'subjects' => []
    ];

    $this->view("marks/index", $data);
  }

  public function addmarks($student_id, $class_id)
  {
    $papers = PaperModel::where('class_id', $class_id)->where('status', '1')->with('class:class_id,name')->with('subject:subject_id,name')->get();

    dump($papers);

    $data = [
      'student_id' => $student_id,
      'papers' => $papers
    ];

    $this->view("marks/create", $data);
  }

  // Get all the students of a particular class
  public function get_students_by_class()
  {
    if (!Request::body('id_class')) {
      throw new AppError($this->route, "Please provide a valid class name", 400);
    }

    // Check if the record has been added to result table
    $result = ResultModel::where('id_paper_term', Request::body('id_paper_type'))
      ->where('id_class', Request::body('id_class'))
      ->first();

    if ($result) {
      throw new AppError($this->route, "Result has already been inserted. Go to the seperate pages to update the record.");
      return;
    }
    
    $paperTerms = PaperType::all();
    $class_id = Request::body('id_class');
    $students = StudentModel::where('class_id', $class_id)->get();
    $classes  = ClassesModel::all();
    $subjects = SubjectModel::where('class_id', $class_id)->get();
    $paper_type_id = Request::body('id_paper_type');
    $dataArray = [];

    foreach ($subjects as $subject){
      $subject_marks_per_term = SubjectPapersMaxNumbers::where("id_subject", $subject->subject_id)->where('id_paper_type', $paper_type_id)->first();
      array_push($dataArray, [
        'subject' => $subject,
        'max_marks' => $subject_marks_per_term->max_numbers
      ]);
    }

    $data = [
      'class_id' => $class_id,
      'paper_type_id' => $paper_type_id,
      'students' => $students,
      'classes' => $classes,
      'subjects' => $dataArray,
      'paper_types' => $paperTerms
    ];

    $this->view("marks/index", $data);
  }

  /**
   * @throws AppError
   */
  public function edit($id)
  {
    $classModel = new ClassesModel();

    $class = $classModel->where('class_id', $id)->first();

    if (!$class)
      throw new AppError($this->route, 'Invalid Class Id', 404);

    $sections = SectionModel::all();

    $classesSection = $class->sections;

    for ($i = 0; $i < sizeof($sections); $i++)
      for ($j = 0; $j < sizeof($classesSection); $j++)
        if (isset($classesSection[$j]))
          if ($sections[$i]['section_id'] == $classesSection[$j]['section_id'])
            $sections[$i]['isSelected'] = true;


    $this->view("classes/edit", [
      'class_name' => $class['name'],
      'sections' => $sections,
      'classesSection' => $classesSection,
      'id' => $id
    ]);

    $this->view("classes/edit");
  }

  public function create()
  {
    $body = Request::body();

    if (!isset($body['marks'])){
      throw new AppError($this->route, "Please provide valid Data sds");
    }

    if (!isset($body['paper_type_id'])){
      throw new AppError($this->route, "Please provide valid Two");
    }

    // Student Id Array
    $students = array_keys($body['marks']);

    $queryArray = [];

    foreach ($students as $student) {
      $result = ResultModel::create([
        'id_paper_term' => $body['paper_type_id'],
        'id_student' => $student,
        'id_class' => $body['id_class']
      ]);

      $subjects = $body['marks'][$student];

      foreach ($subjects as $subject_id => $value) {
        array_push($queryArray, [
          'id_result' => $result->result_id,
          'id_subject' => $subject_id,
          'obtained_marks' => $value['subjective'] + $value['objective']
        ]);
      }
    }

    ResultItemModel::insert($queryArray);

    return redirect($this->route, [
      'type' => "success",
      'message' => "Record saved successfully"
    ]);
  }

  /**
   * @throws AppError
   */
  public function update($id)
  {

    $class = ClassesModel::where("class_id", $id)->first();

    // Data Not Found
    if (!$class)
      throw new AppError($this->route, 'Invalid class Id', 404);

    // Class Name is not empty
    if (!Request::body('class_name'))
      throw new AppError($this->route . "/edit/" . $id, 'Please provide a valid class name', 402);

    $class['name'] = $_POST['class_name'];

    // Delete the existing sections
    $sections = $class->sections;

    $query = [];

    foreach (Request::body('section_id') as $section)
      array_push($query, [
        'class_id' => $id,
        'section_id' => $section
      ]);

    foreach ($sections as $section)
      ClassesSectionModel::where('class_id', $id)->where('section_id', $section['section_id'])->delete();

    ClassesSectionModel::insert($query);

    $class->save();

    return redirect("classes", [
      'type' => 'success',
      'message' => "Record updated successfully"
    ]);
  }

  public function delete($id)
  {
    try {
      $deleted = ClassesModel::where('class_id', $id)->delete();

      // Delete all the relations
      ClassesSectionModel::where('class_id', $id)->delete();

      redirect($this->route, [
        'type' => 'success',
        'message' => "Record Deleted successfully"
      ]);
    } catch (Exception $ex) {
      throw new AppError($this->route, "You can't perform this action as the other data depends on this.", 23000);
    }
  }

  public function class_sections($class_id)
  {
    $sections = ClassesModel::where('class_id', $class_id)->first();

    if ($sections){
      $sections = $sections->section;
    }

    if (!$sections)
      throw new ApiError('No Record Found', 404);

    echo json_encode($sections);
  }

  public function class_subjects($class_id)
  {
    $subjects = ClassesModel::where('class_id', $class_id)->first();


    if ($subjects){
      $subjects = $subjects->subjects;
    }

    if (!$subjects)
      throw new ApiError('No Record Found', 404);

    echo json_encode($subjects);
  }
}
