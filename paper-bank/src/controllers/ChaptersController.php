<?php

class Chapters  extends Controller
{

  protected $route = 'chapters';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    $data = Chapter::orderBy('chapter_no', 'asc')
    ->where('date_deleted','=', NULL)
    ->get();
    $classes = ClassesModel::all();

    $data = [
      'chapters' => $data,
      'classes' => $classes
    ];

    $this->view("chapters/index", $data);
  }

  public function add()
  {
    $classes = ClassesModel::where('is_deleted','!=',1)
    ->where('class_status','=',1)
    ->get();
    $data = [
      'classes' => $classes
    ];

    $this->view("chapters/create", $data);
  }

  public function create()
  {
    try {
      $body = Request::body();

      $ch_no_exist =  Chapter::where('id_subject', '=', $body['id_subject'])
      ->where('chapter_no', '=',  $body['chapter_no'])
      ->where('id_deleted', '=',  NULL)
      ->first();

      if (!$this->is_unique_name($body['chapter_name'])){
        return redirect($this->route, [
          'type' => 'error',
          'message' => "Error! A Chapter with the same name already exist"]);
      }

      if(isset($ch_no_exist)){
        return redirect($this->route, [
          'type' => 'error',
          'message' => "Error!A Chapter with same no. already exist for this Subject..."
        ]);
      }
      else{
        $chaper = Chapter::create([
          'id_subject' => $body['id_subject'],
          'chapter_no' => $body['chapter_no'],
          'chapter_name' => $body['chapter_name'],
          'id_added' => Auth::user_id()
        ]);
      }
      
      

      if(!$chaper){
        return redirect($this->route, [
          'type' => 'error',
          'message' => "Error! While Creating a Record."
        ]);
      }
      
      
        

      // Everything went well redirect the user
      return redirect($this->route, [
        'type' => 'success',
        'message' => "Record created successfully"
      ]);
    } catch (Exception $ex) {
      return redirect($this->route, [
        'type' => 'error',
        'message' => "Can not perform this action. Connect your developer"
      ]);
    }
  }

  public function is_unique_name($name){
    $chapter = Chapter::where('chapter_name','=', $name)
    ->where('id_deleted','=', NULL)
    ->get();
    if ($chapter->count() > 0){
      return false;
    }

    return  true;
  }

  public function edit($id)
  {
    $chapter =  Chapter::where('chapter_id', $id)->first();
    $subject = $chapter->subject;
    $class = $subject->class;

    $data = [
      'chapter_id' => $id,
      'subject' => $subject,
      'class' => $class,
      'chapter' => $chapter
    ];

    $this->view("chapters/edit", $data);
  }

  public function delete($id)
  {

    $chapter = Chapter::where('chapter_id', $id)->first();

    if (!$chapter)
      throw new AppError($this->route, "Invalid Id is provided");

    $chapter->id_deleted = Auth::user_id();
    $chapter->date_deleted = \Carbon\Carbon::now();
    $isSaved = $chapter->save();

    if (!$isSaved)
      throw  new AppError($this->route, "Error! Deleting your record..");

    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Record Deleted successfully.'
    ]);

  }

  public function update($id)
  {

    $body = Request::body();

    $chapter = Chapter::where('chapter_id', $id)->first();

    if (!$chapter)
      throw new AppError($this->route, "Invalid Id is provided");

    $chapter->chapter_name = $body['chapter_name'];
    $chapter->id_modify = Auth::user_id();
    $chapter->date_modify = \Carbon\Carbon::now();
    $isSaved = $chapter->save();

    if (!$isSaved)
      throw  new AppError($this->route, "Error updating your record..");

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Record updated successfully"
    ]);
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

  public function filter_questions()
  {

    $body = Request::body();

    $queryArray = [
      ['id_class', '=', $body['id_class']]
    ];

    if (isset($body['id_subject']) && !empty($body['id_subject'])  && $body['id_subject'] != -1)
      array_push($queryArray, [
        'id_subject', '=', $body['id_subject']
      ]);

    if (isset($body['id_chapter']) && !empty($body['id_chapter']) && $body['id_chapter'] != -1)
      array_push($queryArray, [
        'id_chapter', '=', $body['id_chapter']
      ]);

    $data = Question::where($queryArray)->get();

    $classes = ClassesModel::all();
    $subjects = SubjectModel::all();

    foreach ($data as $question) {
      $question['class_name'] = $question->class->class_name;
      $question['subject_name'] = $question->subject->subject_name;
      $question['chapter_name'] = $question->chapter->chapter_name;

      if ($question['is_objective'])
        $question['type'] = 'Objective';
      else
        $question['type'] = 'Subjective';
    }

    $data = [
      'questions' => $data,
      'classes' => $classes,
      'subjects' => $subjects,
    ];

    $this->view("questions/index", $data);
  }

  public function topics($chapter_id){
    $chapter = Chapter::where('chapter_id', $chapter_id)->first();

    if (!$chapter){
      echo json_encode([
        'code' => 404,
        'message' => 'No record exist'
      ]);
    }

    $topics = $chapter->topics;

    echo json_encode($topics);

  }
}
