<?php

class Papers extends Controller
{
  protected $route = "papers";

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    $classes = ClassesModel::all();
    $papers = PaperModel::all();
    $paperTypes = PaperType::all();

    $data = [
      'classes' => $classes,
      'papers' => $papers,
      'paper_type' => $paperTypes,
    ];

    $this->view("papers/index", $data);
  }

  public function get_max_marks($subject_id, $paperTypeId)
  {
    $rows = SubjectPapersMaxNumbers::where('id_subject', $subject_id)
      ->where('id_exam_type', $paperTypeId)
      ->first()->toJson();
    echo $rows;
  }

  public function generate($paper_id = null)
  {
      $body = Request::body();

      $class_id = $body['class_id'];

      $subject_id = $body['subject_id'];

      $query = $body['query'];

      $chapters = $query['chapter_id'];

      $noOfObjectiveQuestions = $body['query']['objective_questions'];

      $noOfSubjectiveQuestions = $body['query']['subjective_questions'];

      $subjectmarks = SubjectPapersMaxNumbers::where("id_exam_type", $body['paper_type'])
        ->where('id_subject', $body['subject_id'])
        ->first();

      $totalSubjectiveQuestions = $subjectmarks->subjective_marks / $_ENV['LONG_MARKS'];
      $totalObjectiveQuestions = $subjectmarks->objective_marks / $_ENV['MCQ_MARKS'];

      $paper = [
          'subjective' => [],
          'objective' => [],
          'class' => ClassesModel::where('class_id', $class_id)->first(),
          'subject' => SubjectModel::where('subject_id', $subject_id)->first(),
          'id_exam_type' => $body['paper_type'],
      ];

      // Subjective Questions.
      for ($i = 0; $i < sizeof($noOfSubjectiveQuestions); $i++) {
          if (!empty($noOfSubjectiveQuestions[$i])) {
              $questions = Question::where('id_class', $class_id)
                ->where('id_subject', $subject_id)
                ->where('id_chapter', $chapters[$i])
                ->where('is_objective', false)
                ->where('parent_id_question', null)
                ->inRandomOrder()
                ->limit($noOfSubjectiveQuestions[$i])
                ->with('sub_questions')
                ->get();

              foreach ($questions as $question) {
                array_push($paper['subjective'], $question);
              }
          }
      }

      // Objective Questions.
      for ($i = 0; $i < sizeof($noOfObjectiveQuestions); $i++) {
          if (!empty($noOfSubjectiveQuestions[$i])) {
              $questions = Question::where('id_class', $class_id)
                  ->where('id_subject', $subject_id)
                  ->where('id_chapter', $chapters[$i])
                  ->where('is_objective', true)
                  ->inRandomOrder()
                  ->limit($noOfObjectiveQuestions[$i])
                  ->get();

              foreach ($questions as $question) {
                  if ($question->is_objective) {
                      $answers = ($question->answers_options);
                      array_push($paper['objective'], [
                          'question' => $question,
                          'options' => $answers
                      ]);
                  } else {
                      array_push($paper['subjective'], $question);
                  }
              }
          }
      }

      if(sizeof($paper['subjective']) != $totalSubjectiveQuestions)
        throw new AppError($this->route, "Please add more subjective questions because the questions stored are less then required");


      if(sizeof($paper['objective']) != $totalObjectiveQuestions)
        throw new AppError($this->route, "Please add more objective questions because the questions stored are less then required");

      Session::set('paper', $paper);

      // Set the value in the session to let know we have to update the paper
      Session::set('update_paper', true);

      if ($paper_id) {
          return redirect("papers/show_paper/$paper_id");
      } else {
          return redirect("papers/show_paper");
      }
  }

  public function show_paper($paper_id = null)
  {
      $data = Session::get("paper");

      if ($paper_id)
          $data['paper_id'] = $paper_id;

      return $this->view("papers/paper-template1", $data);
  }

//  public function save_generated($paper_id = null)
//  {
//      $paper = Session::get('paper');
//
//      // Create New Paper Table
//      $newPaper = PaperModel::create([
//          'id_class' => $paper['class']->class_id,
//          'id_subject' => $paper['subject']->subject_id,
//          'id_added' => Auth::user_id(),
//          'id_exam_type' => $paper['id_exam_type']
//      ]);
//
//      for ($i = 0; $i < sizeof($paper['objective']); $i++) {
//          PaperQuestions::create([
//              'id_paper' => $newPaper->paper_id,
//              'id_question' => $paper['objective'][$i]['question']->question_id
//          ]);
//      }
//
//      for ($i = 0; $i < sizeof($paper['subjective']); $i++) {
//          PaperQuestions::create([
//              'id_paper' => $newPaper->paper_id,
//              'id_question' => $paper['subjective'][$i]->question_id
//          ]);
//
//        foreach ($paper['subjective'][$i]->sub_questions as $subquestion){
//          PaperQuestions::create([
//            'id_paper' => $newPaper->paper_id,
//            'id_question' => $subquestion->question_id
//          ]);
//        }
//      }
//
//      if ($paper_id) {
//          PaperModel::where('paper_id', $paper_id)->delete();
//          $paper_questions = PaperQuestions::where('id_paper', $paper_id)->get();
//          foreach ($paper_questions as $paper_question) {
//            $paper_question->delete();
//          }
//
//
//          return redirect($this->route, [
//              'type' => 'success',
//              'message' => "Old Record Have been deleted and new paper have been generated"
//          ]);
//      }
//
//      return redirect($this->route, [
//          'type' => 'success',
//          'message' => "Paper saved successfully"
//      ]);
//  }

  public function save_generated($paper_id = null)
  {
    $paper = Session::get('paper');

    if ($paper_id) {
      $paperDB = PaperModel::where('paper_id', $paper_id)->first();
      $paperDB->id_class = $paper['class']->class_id;
      $paperDB->id_subject = $paper['subject']->subject_id;
      $paperDB->id_modify = Auth::user_id();
      $paperDB->id_exam_type = $paper['id_exam_type'];
      $paperDB->date_modify = \Carbon\Carbon::now();
      $paperDB->save();
      $newPaper = $paperDB->refresh();

      $paper_questions = PaperQuestions::where('id_paper', $paper_id)->get();
      foreach ($paper_questions as $paper_question) {
        $paper_question->delete();
      }

    }else {
      // Create New Paper Table
      $newPaper = PaperModel::create([
        'id_class' => $paper['class']->class_id,
        'id_subject' => $paper['subject']->subject_id,
        'id_added' => Auth::user_id(),
        'id_exam_type' => $paper['id_exam_type']
      ]);
    }


    for ($i = 0; $i < sizeof($paper['objective']); $i++) {
      PaperQuestions::create([
        'id_paper' => $newPaper->paper_id,
        'id_question' => $paper['objective'][$i]['question']->question_id
      ]);
    }

    for ($i = 0; $i < sizeof($paper['subjective']); $i++) {
      PaperQuestions::create([
        'id_paper' => $newPaper->paper_id,
        'id_question' => $paper['subjective'][$i]->question_id
      ]);

      foreach ($paper['subjective'][$i]->sub_questions as $subquestion){
        PaperQuestions::create([
          'id_paper' => $newPaper->paper_id,
          'id_question' => $subquestion->question_id
        ]);
      }
    }

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Paper saved successfully"
    ]);
  }

  public function print($paper_id)
  {
      $paper = PaperModel::where('paper_id', $paper_id)->first();

      if (!$paper)
          throw new AppError($this->route, "Invalid Paper Id is provided", 404);

      $questionItems = $paper->questions;
      $class = $paper->class;
      $subject = $paper->subject;

      $data = [
          'class' => $class,
          'subject' => $subject,
          'objective' => [],
          'subjective' => []
      ];

      foreach ($questionItems as $item) {
        $question = $item->question;

        $sub_questions = $question->sub_questions;

        if ($question->is_objective) {
            $answer_options = $question->answers_options;
            array_push($data['objective'],
              [
                'question' => $question,
                'answer_options' => $answer_options
              ]);
        } else if ($question->parent_id_question == null) {
          $question['sub_questions'] = $sub_questions;
          array_push($data['subjective'], $question);
        }
      }

      $this->viewWithOutHeaderAndFooter("papers/print-template2", $data);
  }

  public function print_objective($paper_id)
  {
      $paper = PaperModel::where('paper_id', $paper_id)->first();

      if (!$paper)
          throw new AppError($this->route, "Invalid Paper Id is provided", 404);

      $questionItems = $paper->questions;
      $class = $paper->class;
      $subject = $paper->subject;


      $data = [
          'class' => $class,
          'subject' => $subject,
          'objective' => [],
          'subjective' => []
      ];

      foreach ($questionItems as $item) {
          $question = $item->question;
          if ($question->is_objective) {
              $answer_options = $question->answers_options;
              array_push($data['objective'], [
                  'question' => $question,
                  'answer_options' => $answer_options
              ]);
          }
      }

      $this->viewWithOutHeaderAndFooter("papers/print_objective", $data);
  }

  public function print_subjective($paper_id)
  {
      $paper = PaperModel::where('paper_id', $paper_id)->first();

      if (!$paper)
          throw new AppError($this->route, "Invalid Paper Id is provided", 404);

      $questionItems = $paper->questions;
      $class = $paper->class;
      $subject = $paper->subject;

      $data = [
        'class' => $class,
        'subject' => $subject,
        'objective' => [],
        'subjective' => [],
      ];

//      foreach ($questionItems as $item) {
//          $question = $item->question;
//          if (!$question->is_objective) {
//            if ($question->parent_id_question == null) {
//              $question['sub_questions'] = $sub_questions;
//              array_push($data['subjective'], $question);
//            }
//            array_push($data['subjective'], $question);
//          }
//      }
    foreach ($questionItems as $item) {
      $question = $item->question;

      $sub_questions = $question->sub_questions;

      if ($question->parent_id_question == null && !$question->is_objective) {
        $question['sub_questions'] = $sub_questions;
        array_push($data['subjective'], $question);
      }
    }

      $this->viewWithOutHeaderAndFooter("papers/print_subjective", $data);
  }

  public function unset($oldQuestionId, $questionType)
  {
      $paper = Session::get('paper');
      $count = 0;

      for ($i = 0; $i < sizeof($paper[$questionType]); $i++) :
          if ($paper[$questionType][$i]){
            if (($paper[$questionType][$i])->question_id == $oldQuestionId && $count < 1) {
                $paper[$questionType][$i]->question_id = -1;
                $count++;
            }
          }
      endfor;

      Session::set('paper', $paper);

      echo json_encode([
          'status' => 'success',
          'message' => "Paper Question updated successfully"
      ]);
  }

  public function set($newId, $questionType)
  {
      $paper = Session::get('paper');
      $count = 0;

      // Get the question
      $question = Question::where('question_id', $newId)->first();

      for ($i = 0; $i < sizeof($paper[$questionType]); $i++) :
        if ($paper[$questionType][$i]) {
          if ($paper[$questionType][$i]->question_id == -1 && $count < 1) {
            // If the Old question Id match then unset it
            $paper[$questionType][$i] = $question;
            $count++;
          }
        }
      //          dump($paper[$questionType][$i]?->question_id);
      endfor;

      Session::set('paper', $paper);

      $question = $question->toJson();

      echo json_encode([
          'status' => 'success',
          'message' => "Paper Question updated successfully",
          'question' => $question
      ]);
  }

  public function getpaper()
  {
      echo json_encode(Session::get('paper'));
  }

  public function edit($paper_id)
  {

      $classes = ClassesModel::all();
      $paper = PaperModel::where('paper_id', $paper_id)->first();
      $subjects = SubjectModel::where('id_class', $paper->id_class)->get();
      $chapters = Chapter::where('id_subject', $paper->id_subject)->get();
      $paper_types = PaperType::all();
      $marks = SubjectPapersMaxNumbers::where('id_subject', $paper->id_subject)->where('id_exam_type', $paper->id_exam_type)->first();

      if (!$paper)
        throw  new AppError($this->route, "Paper does not exit with the given Id.");

      $list = [];

      foreach ($chapters as $chapter) {
          $list[$chapter->chapter_id] = [
              'subjective_count' => 0,
              'objective_count' => 0
          ];
      }

      $rows = PaperQuestions::where('id_paper', $paper_id)
          ->with("question:question_id,question,id_chapter,no_of_lines,is_objective,parent_id_question")
          ->get();

      foreach ($rows as $row) {
          if ($row->question->is_objective) {
              $list[$row->question->id_chapter]['objective_count']++;
          }

          if (!$row->question->is_objective) {
              if($row->question->parent_id_question == null){
                $list[$row->question->id_chapter]['subjective_count']++;
              }
          }
      }

      $data = [
        'paper_id' => $paper_id,
        'classes' => $classes,
        'paper' => $paper,
        'subjects' => $subjects,
        'chapters' => $chapters,
        'count' => $list,
        'paper_type' => $paper_types,
        'subjective_marks' => $marks->subjective_marks,
        'objective_marks' => $marks->objective_marks,
        'total_marks' => $marks->subjective_marks + $marks->objective_marks
      ];

      $this->view("papers/edit", $data);
  }

  public function delete($paper_id)
  {
    return $this->underdevelopment_redirect();

    $paper = PaperModel::where('paper_id', $paper_id);
    $paper->id_deleted = Auth::user()->user_id;
    $paper->save();
    $paper->delete();

    return redirect($this->route, [
        'type' => 'success',
        'message' => "Record deleted successfully"
    ]);
  }

  public function class($class_id)
  {
      $papers = PaperModel::where('class_id', $class_id)->get()->toJson();


      echo ($papers);
  }

  public function underdevelopment_redirect()
  {
    return redirect($this->route, [
      'type' => 'error',
      'message' => "This feature is underdevelopment. will be available soon"
    ]);
  }
}
