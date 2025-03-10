<?php

class Result extends Controller
{
  protected $route = "result";

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    $classes = ClassesModel::all();
    $paperTypes = PaperType::all();

    $data = [
      'classes' => $classes,
      'paper_types' => $paperTypes
    ];

    $this->view("results/index", $data);
  }

  public function print()
  {
    $data = [
      'results' => [],
      'students' => []
    ];

    $body = Request::body();
    $class = ClassesModel::where('class_id', $body['id_class'])->with("students")->first();
    $students = $class->students;

    $data['students'] = $students;

    // Get Marks for each student
    foreach ($students as $student ){
      $result = ResultModel::where('id_paper_term', $body['id_paper_type'])
        ->where('id_student', $student->student_id)
        ->with("resultItems.subject")
        ->first();
      if (!$result)
        throw new AppError($this->route, "There is no record saved for the given query");
      array_push($data['results'], $result);
    }

    $this->viewWithOutHeaderAndFooter("results/print", $data);
  }
}
