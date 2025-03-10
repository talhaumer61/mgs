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
    $data = Chapter::orderBy('date_added', 'desc')->get();
    $classes = ClassesModel::all();

    $data = [
      'chapters' => $data,
      'classes' => $classes
    ];

    $this->view("chapters/index", $data);
  }

  public function add()
  {
    $subjects = SubjectModel::all();
    $classes = ClassesModel::all();

    $data = [
      'subjects' => $subjects,
      'classes' => $classes
    ];

    $this->view("chapters/create", $data);
  }

  public function create()
  {
    try {
      $body = Request::body();

      $chaper = Chapter::create([
        'id_subject' => $body['id_subject'],
        'chapter_name' => $body['chapter_name'],
        'id_added' => Auth::user_id()
      ]);

      if(!$chaper)
        throw new AppError($this->route, "Error creating new record. please try again");

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
    return redirect($this->route, [
      'type' => 'error',
      'message' => 'You are not allowed to delete any question.'
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

  public function create_backup()
  {
    //      if ($body['type'] == 'subjective')
    //          $marks = $_ENV['LONG_MARKS'];
    //
    //      if ($body['question_subjective_type'] == 'short') $marks = $_ENV['SHORT_MARKS'];
    //
    //      $parentQuestion = null;
    //
    //      if ($body['type'] == 'subjective'){
    //          for ($i = 0; $i < sizeof($body['question']); $i++){
    //              if (isset($body['no_of_subjective_parts']) && $body['no_of_subjective_parts'] > 0 && $data['question_subjective_type'] == 'long'){
    //                  if (isset($body['question_subjective_type']) && $i == 0){
    //                      $parentQuestion = Question::create([
    //                          'question' => $body['question'][$i],
    //                          'is_objective' => false,
    //                          'class_id' => $body['class_id'],
    //                          'subject_id' => $body['subject_id'],
    //                          'chapter_id' => $body['chapter_id'],
    //                          'marks' => $marks,
    //                          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
    //                          'no_of_lines' => null,
    //                          'dir_ltr' => isset($body['is_ltr']) && $body['is_ltr'][$i] ? false : true
    //
    //                      ]);
    //                  }else{
    //                      $isSaved = Question::create([
    //                          'question' => $body['question'][$i],
    //                          'is_objective' => $body['type'][$i] == 'objective' ? false : true,
    //                          'class_id' => $body['class_id'],
    //                          'subject_id' => $body['subject_id'],
    //                          'chapter_id' => $body['chapter_id'],
    //                          'marks' => number_format($marks / $body['no_of_subjective_parts'], 1),
    //                          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
    //                          'no_of_lines' => $body['no_of_lines'][$i] ?? null,
    //                          'dir_ltr' => $body['is_ltr'][$i] ? false : true,
    //                          'parent_id_question' => $parentQuestion->question_id
    //                      ]);
    //                  }
    //              } else {
    //
    //                  $isSaved = Question::create([
    //                      'question' => $body['question'][$i],
    //                      'is_objective' => $body['type'][$i] == 'objective' ? true : false,
    //                      'class_id' => $body['class_id'],
    //                      'subject_id' => $body['subject_id'],
    //                      'chapter_id' => $body['chapter_id'],
    //                      'marks' => $marks,
    //                      'difficulty_level' => $body['difficulty_level'][$i] ?? null,
    //                      'no_of_lines' => $body['no_of_lines'][$i] ?? null,
    //                      'dir_ltr' => $body['is_ltr'][$i] ? false : true,
    //                      'parent_id_question' => $parentQuestion->question_id ?? null
    //                  ]);
    //              }
    //
    //          }
    //      }
    //
    //      if ($body['type'] == 'objective'){
    //
    //          $isSaved = Question::create([
    //              'question' => $body['question'][0],
    //              'is_objective' => $body['type'][$i] == 'objective' ? false : true,
    //              'class_id' => $body['class_id'],
    //              'subject_id' => $body['subject_id'],
    //              'chapter_id' => $body['chapter_id'],
    //              'marks' => $marks,
    //              'dir_ltr' => $body['is_ltr'][$i] ? false : true
    //          ]);
    //
    //          $question_id = $isSaved->question_id ;
    //
    //          $optionData = [];
    //
    //          $options = $body['options'];
    //
    //          foreach ($options as $key => $option) {
    //              array_push($optionData,  [
    //                  'question_id' => $question_id,
    //                  'answer_option' => $option,
    //                  'is_correct' => $key == $body['correct_option'] ? true : false
    //              ]);
    //          }
    //
    //          $optionsSaved = ObjectiveAnswers::insert($optionData);
    //      }
    //
    //      dump($body);
    //
    //      if (!$isSaved)
    //          throw new AppError($this->route, "Error creating the record, Please make sure you are providing valid informatiion", 400);
    //
    ////    $question_id = $isSaved->question_id ?? 2;
    ////
    ////    if ($body['type'] == 'objective') {
    ////      $optionData = [];
    ////
    ////      $options = $body['options'];
    ////
    ////      foreach ($options as $key => $option) {
    ////        array_push($optionData,  [
    ////          'question_id' => $question_id,
    ////          'answer_option' => $option,
    ////          'is_correct' => $key == $body['correct_option'] ? true : false
    ////        ]);
    ////      }
    ////
    ////      $optionsSaved = ObjectiveAnswers::insert($optionData);
    ////    }

    //    return redirect($this->route, [
    //      'type' => 'success',
    //      'message' => "Record inserted successfully"
    //    ]);
  }
}
