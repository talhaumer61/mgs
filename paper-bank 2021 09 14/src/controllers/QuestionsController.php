<?php
class Questions  extends Controller
{

  public function __construct()
  {
    $this->authMiddleware();
  }

  protected $route = 'questions';

  public function index()
  {
    $data = Question::orderBy('question_id', 'desc')->get();
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

  public function add()
  {
    $chapters = Chapter::all();
    $classes  = ClassesModel::all();
    $subjects = SubjectModel::all();
    $levels   = DifficultyLevel::all();

    $data = [
      'classes'      => $classes,
      'subjects'     => $subjects,
      'chapters'     => $chapters,
      'levels'       => $levels,
      'questionType' => getQuestionType(),
      'qType'        => getQType(),
      'Yesno'        => getYesno(),
    ];

    $this->view("questions/create", $data);
  }

  public function create()
  {
    try {
      $body = Request::body();

      // Subjective
      // Case 1) Question is Short Subjective
      if (isset($body['question_subjective_type']) && $body['question_subjective_type'] == "2") {
        // Get the marks for short question
        $marks = $body['marks'];
        $i = 0;

        // Verify the question direction
        $is_ltr = isset($body['is_ltr']) && $body['is_ltr'][$i];
        $status = $body['status'][$i];
        Question::create([
          'question' => $body['question'][$i],
          'question_urdu' => $body['question_urdu'][$i],
          'is_objective' => false, // It is always going to be false in this case
          'id_class' => $body['class_id'],
          'id_subject' => $body['subject_id'],
          'id_chapter' => $body['chapter_id'],
          'marks' => $marks,
          'question_status' => $status,
          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
          'no_of_lines' => $body['no_of_lines'][$i] ?? null,
          // 'dir_ltr' => $is_ltr,
          'question_type' => $body['question_subjective_type'],
        ]);
      }

      // Case 2) Question is Long Subjective with no parts
      if (isset($body['question_subjective_type']) && $body['question_subjective_type'] == "1" && empty($body['no_of_subjective_parts'])) {
        // Get the marks for short question
        $marks = $body['marks'];
        $i = 0;

        // Verify the question direction
        $status = $body['status'][$i];
        Question::create([
          'question' => $body['question'][$i],
          'question_urdu' => $body['question_urdu'][$i],
          'is_objective' => false, // It is always going to be false in this case
          'id_class' => $body['class_id'],
          'id_subject' => $body['subject_id'],
          'id_chapter' => $body['chapter_id'],
          'marks' => $marks,
          'question_status' => $status,
          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
          'no_of_lines' => $body['no_of_lines'][$i] ?? null,
          'question_type' => $body['question_subjective_type']
          // 'dir_ltr' => $is_ltr
        ]);
      }

      // Case 3) Question is Long with parts
      if (isset($body['question_subjective_type']) && $body['question_subjective_type'] == "1" && !empty($body['no_of_subjective_parts']) && $body['no_of_subjective_parts'] > 0) {
        // Get the marks for short question
        $marks = $body['marks'];

        // Set the parent Question
        $parent_question = null;
        $has_sub = 1;

        for ($i = 0; $i <= $body['no_of_subjective_parts']; $i++) {
          // Verify the question direction
          $is_ltr = isset($body['is_ltr']) && $body['is_ltr'][$i];
          $status = $body['status'][$i];

          // Insert data into table
          $newQuestion = Question::create([
            'question' => $body['question'][$i],
            'question_urdu' => $body['question_urdu'][$i],
            'is_objective' => false, // It is always going to be false in this case
            'id_class' => $body['class_id'],
            'id_subject' => $body['subject_id'],
            'id_chapter' => $body['chapter_id'],
            'marks' => $i == 0 ? $marks : number_format($marks / $body['no_of_subjective_parts'], 1),
            'difficulty_level' => $i == 0 ? null : $body['difficulty_level'][$i] ?? null,
            'no_of_lines' => $i == 0 ? null : $body['no_of_lines'][$i] ?? null,
            //'dir_ltr' => $is_ltr,
            'question_status' => $status,
            'parent_id_question' => $parent_question,
            'question_type' => $body['question_subjective_type'],
            'has_sub' => $has_sub
          ]);

          // Check if the question was inserted
          if ($newQuestion && $i == 0) {
            $parent_question = $newQuestion->question_id;
            $has_sub = 0;
          }
        }
      }

      // Objective
      // Case 4) Question is objective & MCQS
      if ($body['type'] == 'objective') {
        // Get the marks for short question
        $marks = $body['marks'];
        $i = 0;
        
        // Verify the question direction
        $is_ltr = isset($body['is_ltr']) && $body['is_ltr'][$i];
        $status = $body['status'][$i];
        $newQuestion = Question::create([
          'question' => $body['question'][$i],
          'question_urdu' => $body['question_urdu'][$i],
          'is_objective' => true, // It is always going to be false in this case
          'id_class' => $body['class_id'],
          'id_subject' => $body['subject_id'],
          'id_chapter' => $body['chapter_id'],
          'marks' => $marks,
          'question_status' => $status,
          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
          'no_of_lines' => $body['no_of_lines'][$i] ?? null,
          'question_type' => $body['question_subjective_type'],
          // 'dir_ltr' => $is_ltr
        ]);

        // Save the objective options to the database.
        for ($j = 0; $j < sizeof($body['options']); $j++) {
          ObjectiveAnswers::create([
            'id_question' => $newQuestion->question_id,
            'answer_option' => $body['options'][$j],
            'is_correct' => $j == $body['correct_option']
          ]);
        }
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
        // 'message' => 
      ]);
    }
  }

  public function edit($id)
  {

    $question = Question::where('question_id', $id)->with('difficulty')->first();
    $chapters = Chapter::where('id_subject', $question->id_subject)->get();

    $classes = ClassesModel::all();
    $subjects = SubjectModel::all();
    $levels = DifficultyLevel::all();
    $show_no_of_lines = sizeof($question->sub_questions) > 1 ;

    $data = [
      'question_id' => $question->question_id,
      'question' => $question,
      'show_no_lines' => $show_no_of_lines,
      'classes' => $classes,
      'subjects' => $subjects,
      'chapters' => $chapters,
      'levels' => $levels
    ];

    $this->view("questions/edit", $data);
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

    $question = Question::where('question_id', $id)->first();
    $question->question         = $body['question'];
    $question->question_urdu    = $body['question_urdu'];
    $question->question_status  = $body['status'];
    $question->difficulty_level = isset($body['difficulty_level']) ? $body['difficulty_level'] : null;
    $question->no_of_lines      = isset($body['no_of_lines']) && $body['no_of_lines'] ? 
                                    $body['no_of_lines'] : 
                                    null;
    // $question->dir_ltr = isset($body['is_ltr']) && $body['is_ltr']  ? true : false;
    $isSaved = $question->save();

    if (!$isSaved)
      throw new AppError($this->route, "Error updating the record, Please make sure you are providing valid information", 400);

    $question_id = $id;

    if ($body['type'] == 'objective') {
      $options = $body['options'];
      $option_ids = $body['option_ids'];

      for($i = 0; $i < sizeof($option_ids); $i++){
        ObjectiveAnswers::where('answer_id', $option_ids[$i])->update([
        'answer_option' => $options[$i],
        'is_correct' => $i == $body['correct_option'] ? true : false
        ]);
      }

    }

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

    if (!isset($body['id_class'])){
      return redirect($this->route);
    }

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
    $class_subjects = ClassesModel::where('class_id', $body['id_class'])->with('subjects')->first();
    $subject_chaptes = Chapter::where('id_subject', $body['id_subject'])->get();
//    return  dump($subject_chaptes);


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
      'id_class' => $body['id_class'],
      'id_subject' => isset($body['id_subject']) ? $body['id_subject'] : null,
      'id_chapter' => isset($body['id_chapter']) ? $body['id_chapter'] : null,
      'questions' => $data,
      'classes' => $classes,
      'subjects' => $subjects,
      'class_subjects' => $class_subjects->subjects,
      'subject_chapters' => $subject_chaptes,
    ];

    $this->view("questions/index", $data);
  }

//  public function create_backup()
//  {
//    //      if ($body['type'] == 'subjective')
//    //          $marks = $_ENV['LONG_MARKS'];
//    //
//    //      if ($body['question_subjective_type'] == 'short') $marks = $_ENV['SHORT_MARKS'];
//    //
//    //      $parentQuestion = null;
//    //
//    //      if ($body['type'] == 'subjective'){
//    //          for ($i = 0; $i < sizeof($body['question']); $i++){
//    //              if (isset($body['no_of_subjective_parts']) && $body['no_of_subjective_parts'] > 0 && $data['question_subjective_type'] == 'long'){
//    //                  if (isset($body['question_subjective_type']) && $i == 0){
//    //                      $parentQuestion = Question::create([
//    //                          'question' => $body['question'][$i],
//    //                          'is_objective' => false,
//    //                          'class_id' => $body['class_id'],
//    //                          'subject_id' => $body['subject_id'],
//    //                          'chapter_id' => $body['chapter_id'],
//    //                          'marks' => $marks,
//    //                          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
//    //                          'no_of_lines' => null,
//    //                          'dir_ltr' => isset($body['is_ltr']) && $body['is_ltr'][$i] ? false : true
//    //
//    //                      ]);
//    //                  }else{
//    //                      $isSaved = Question::create([
//    //                          'question' => $body['question'][$i],
//    //                          'is_objective' => $body['type'][$i] == 'objective' ? false : true,
//    //                          'class_id' => $body['class_id'],
//    //                          'subject_id' => $body['subject_id'],
//    //                          'chapter_id' => $body['chapter_id'],
//    //                          'marks' => number_format($marks / $body['no_of_subjective_parts'], 1),
//    //                          'difficulty_level' => $body['difficulty_level'][$i] ?? null,
//    //                          'no_of_lines' => $body['no_of_lines'][$i] ?? null,
//    //                          'dir_ltr' => $body['is_ltr'][$i] ? false : true,
//    //                          'parent_id_question' => $parentQuestion->question_id
//    //                      ]);
//    //                  }
//    //              } else {
//    //
//    //                  $isSaved = Question::create([
//    //                      'question' => $body['question'][$i],
//    //                      'is_objective' => $body['type'][$i] == 'objective' ? true : false,
//    //                      'class_id' => $body['class_id'],
//    //                      'subject_id' => $body['subject_id'],
//    //                      'chapter_id' => $body['chapter_id'],
//    //                      'marks' => $marks,
//    //                      'difficulty_level' => $body['difficulty_level'][$i] ?? null,
//    //                      'no_of_lines' => $body['no_of_lines'][$i] ?? null,
//    //                      'dir_ltr' => $body['is_ltr'][$i] ? false : true,
//    //                      'parent_id_question' => $parentQuestion->question_id ?? null
//    //                  ]);
//    //              }
//    //
//    //          }
//    //      }
//    //
//    //      if ($body['type'] == 'objective'){
//    //
//    //          $isSaved = Question::create([
//    //              'question' => $body['question'][0],
//    //              'is_objective' => $body['type'][$i] == 'objective' ? false : true,
//    //              'class_id' => $body['class_id'],
//    //              'subject_id' => $body['subject_id'],
//    //              'chapter_id' => $body['chapter_id'],
//    //              'marks' => $marks,
//    //              'dir_ltr' => $body['is_ltr'][$i] ? false : true
//    //          ]);
//    //
//    //          $question_id = $isSaved->question_id ;
//    //
//    //          $optionData = [];
//    //
//    //          $options = $body['options'];
//    //
//    //          foreach ($options as $key => $option) {
//    //              array_push($optionData,  [
//    //                  'question_id' => $question_id,
//    //                  'answer_option' => $option,
//    //                  'is_correct' => $key == $body['correct_option'] ? true : false
//    //              ]);
//    //          }
//    //
//    //          $optionsSaved = ObjectiveAnswers::insert($optionData);
//    //      }
//    //
//    //      dump($body);
//    //
//    //      if (!$isSaved)
//    //          throw new AppError($this->route, "Error creating the record, Please make sure you are providing valid informatiion", 400);
//    //
//    ////    $question_id = $isSaved->question_id ?? 2;
//    ////
//    ////    if ($body['type'] == 'objective') {
//    ////      $optionData = [];
//    ////
//    ////      $options = $body['options'];
//    ////
//    ////      foreach ($options as $key => $option) {
//    ////        array_push($optionData,  [
//    ////          'question_id' => $question_id,
//    ////          'answer_option' => $option,
//    ////          'is_correct' => $key == $body['correct_option'] ? true : false
//    ////        ]);
//    ////      }
//    ////
//    ////      $optionsSaved = ObjectiveAnswers::insert($optionData);
//    ////    }
//
//    //    return redirect($this->route, [
//    //      'type' => 'success',
//    //      'message' => "Record inserted successfully"
//    //    ]);
//  }
}
