<?php

class _Subjects  extends Controller
{

    protected $route = 'subjects';

    public function __construct()
    {
        $this->authMiddleware();
    }


  public function index()
  {
    $data = SubjectModel::with('class')->get();
    $classes = ClassesModel::all();
    $paperTypes = PaperType::all();

    foreach ($data as $subject) {
      $class = $subject->class;
      $subject['class_name'] = $class['name'];
    }

    $data = [
      'subjects' => $data,
      'classes' => $classes,
      'paper_types' => $paperTypes
    ];

    $this->view("subjects/index", $data);
  }

  public function add()
  {
    $classes = ClassesModel::all();
    $paperTypes = PaperType::all();

    $data = [
      "classes" => $classes,
      'paper_types' => $paperTypes
    ];

    $this->view("subjects/create", $data);
  }

  public function create()
  {
    $body = Request::body();

    $newSubject = SubjectModel::create([
      'name' => $body['subject_name'],
      'class_id' => $body['class_id']
    ]);

    $id_subject = $newSubject->subject_id;

    // Prepare the query array
    $query = [];

    for ($i = 0; $i < sizeof($body['paper_type']); $i++){
        array_push($query, [
           'id_subject' => $id_subject,
           'id_paper_type' => $body['paper_type'][$i],
            'no_of_objective' => $body['no_of_objective'][$i] *  1, // Each objective question worth of 1 marks
            'no_of_subjective' => $body['no_of_subjective'][$i] *  5, // Each subjective question worth of 5 marks
            'max_numbers' => ($body['no_of_objective'][$i] *  1) + ($body['no_of_subjective'][$i] *  5) // Total marks of objective and subjective questions
        ]);
    }

    SubjectPapersMaxNumbers::insert($query);

    if (!$newSubject)
      throw new AppError($this->route, "Error creating the record, Please make sure you are providing valid informatiion", 400);

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Record inserted successfully"
    ]);
  }

  public function edit($id)
  {

    $subject = SubjectModel::where('subject_id', $id)->first();

    if (!$subject)
      throw new AppError($this->route, "No record found for the givin Id", 404);

    $classes = ClassesModel::all();
    $paperTypes = PaperType::all();



    $type_array = [];

    foreach ($paperTypes as $type){

      $max_marks_for_subject = SubjectPapersMaxNumbers::where('id_paper_type', $type->paper_type_id)
        ->where('id_subject', $subject->subject_id)
        ->first();

      array_push($type_array, [
        'parent' => $type,
        'child' => $max_marks_for_subject
      ]);

    }

    $data = [
      'subject' => $subject,
      'classes' => $classes,
      'id' => $id,
      'paper_types' => $paperTypes,
      'subject_term_marks' => $type_array
    ];

    $this->view('subjects/edit', $data);
  }

  public function delete($id)
  {
    SubjectModel::where('subject_id', $id)->delete();
    return redirect("subjects/index", [
      'type' => 'success',
      'message' => 'Record Deleted successfully'
    ]);
  }

  public function update($id)
  {
    $body = Request::body();

//    return dump($body);

    // Update the Subject name and class
    $subject = SubjectModel::where('subject_id', $id)->first();
    $subject->name = $body['subject_name'];
    $subject->class_id = $body['class_id'];

    $isSaved = $subject->save();

    // Update the number record
    for ($i = 0; $i < sizeof($body['record_id']); $i++){
      if ($body['record_id'][$i] != -1){
        // Update the record
        $record = SubjectPapersMaxNumbers::where('id', $body['record_id'][$i])->first();
        $record->no_of_objective = $body['no_of_objective'][$i] * 1;
        $record->no_of_subjective = $body['no_of_subjective'][$i] * $_ENV['LONG_MARKS'];
        $record->max_numbers = ($body['no_of_objective'][$i] * 1) + ($body['no_of_subjective'][$i] * $_ENV['LONG_MARKS']);
        $record->save();
      }else{
        SubjectPapersMaxNumbers::create([
          'no_of_objective' => $body['no_of_objective'][$i] * 1,
          'no_of_subjective' => $body['no_of_subjective'][$i] * $_ENV['LONG_MARKS'],
          'max_numbers' => ($body['no_of_objective'][$i] * $_ENV['LONG_MARKS']) + ($body['no_of_subjective'][$i] * 5),
          'id_paper_type' => $body['paper_type'][$i],
          'id_subject' => $id
        ]);
      }
    }

    if (!$isSaved)
      throw new AppError($this->route, "Invalid student Id", 400);

    return redirect($this->route, ['type' => 'success', 'message' => "Record updated successfully"]);
  }

  // Get all the students of a particular class
  public function get_subjects_by_class()
  {

    if (!Request::body('id_class')) {
      throw new AppError($this->route, "Please provide a valid class name", 400);
    }

    $class_id = Request::body('id_class');
    $subjects = SubjectModel::where('class_id', $class_id)->get();
    $classes = ClassesModel::all();


    foreach ($subjects as $subject) {
      $class = $subject->class;
      $subject['class_name'] = $class['name'];
    }

    $data = [
      'subjects' => $subjects,
      'classes' => $classes
    ];

    $this->view("subjects/index", $data);
  }

  public function subject_chapters($subject_id)
  {
    $chapters = SubjectModel::where('subject_id', $subject_id)->first();

    if ($chapters){
      $chapters = $chapters->chapters;
    }

    if (!$chapters)
      throw new ApiError('No Record Found', 404);

    echo json_encode($chapters);
  }
}
