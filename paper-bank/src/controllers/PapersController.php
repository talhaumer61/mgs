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
    $classes = ClassesModel::where('class_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();

    $papers = PaperModel::where('id_deleted','=',NULL)
    ->orderBy('paper_id', 'desc')
    ->get();

    $id = PaperModel::orderBy('paper_id', 'desc')->value('paper_id');

    

    $paperTypes = ExamTypeModel::where('is_deleted','!=',1)
    ->where('type_status','=',1)
    ->get();

    $paperStyles = PaperStyle::where('id_deleted','=',NULL)
    ->where('paper_style_status','=',1)
    ->get();

    $data = [
      'classes' => $classes,
      'papers' => $papers,
      'id' => $id,
      'paper_type' => $paperTypes,
      'paperStyles' => $paperStyles
    ];

    $this->view("papers/index", $data);
  }



  public function generate($paper_id = null)
  {
      $body = Request::body();
       
      if($paper_id){
        $newRoute = "papers/edit/$paper_id";
      }else{
        $newRoute = $this->route;
      }
      
      $exam_type = $body['paper_type'];
      $class_id = $body['class_id'];
      $subject_id = $body['id_subject'];
      $chapter_from = $body['id_chapter'];
      $chapter_to = $body['id_chapter_to'];
      $paper_stytle = $body['paper_style'];
      $paper_time = $body['paper_time'];
      $no_mcqs = $body['no_mcqs'];
      $marks_mcq = $body['marks_mcq'];
      $no_short_questions = $body['no_short_questions'];
      $marks_short_question = $body['marks_short_question'];
      $no_long_questions = $body['no_long_questions'];
      $marks_long_question = $body['marks_long_question'];
      $lines_short_question = $body['lines_short_question'];
      $lines_long_question = $body['lines_long_question'];
      
      
      // Long Question.
        $long_questions = Question::where('id_class', $class_id)
          ->where('id_subject', $subject_id)
          ->where('id_chapter', '>=', $chapter_from)
          ->where('id_chapter', '<=', $chapter_to)
          ->where('id_question_type', '=', 1)
          ->inRandomOrder()
          ->limit($no_long_questions)
          ->get();
          $paperLongQuestion = array();

        foreach ($long_questions as $questionL) {
          array_push($paperLongQuestion, $questionL);
        }

        if(sizeof($paperLongQuestion) != $no_long_questions)
        throw new AppError($newRoute, "Please add more Long questions because the questions stored are less then required");

      // Short Question.
        $short_questions = Question::where('id_class', $class_id)
        ->where('id_subject', $subject_id)
        ->where('id_chapter', '>=', $chapter_from)
        ->where('id_chapter', '<=', $chapter_to)
        ->where('id_question_type', '=', 2)
        ->inRandomOrder()
        ->limit($no_short_questions)
        ->get();
          $paperShortQuestion = array();
        foreach ($short_questions as $questionS) {
          array_push($paperShortQuestion, $questionS);
        }

        if(sizeof($paperShortQuestion) != $no_short_questions)
        throw new AppError($newRoute, "Please add more Short questions because the questions stored are less then required");

      // MCQs Question.
        $mcq_questions = Question::where('id_class', $class_id)
        ->where('id_subject', $subject_id)
        ->where('id_chapter', '>=', $chapter_from)
        ->where('id_chapter', '<=', $chapter_to)
        ->where('id_question_type', '=', 3)
        ->with('answers_options')
        ->inRandomOrder()
        ->limit($no_mcqs)
        ->get();
          $paperMcqQuestion = array();
        foreach ($mcq_questions as $questionM) {
          array_push($paperMcqQuestion, $questionM);
        }

      if(sizeof($paperMcqQuestion) != $no_mcqs)
      throw new AppError($newRoute, "Please add more MCQ questions because the questions stored are less then required");

      $paper = [
        'long_questions' => $long_questions,
        'short_questions' => $short_questions,
        'mcq_questions' => $mcq_questions,
        'exam_type'=> $exam_type,
        'class_id' => $class_id,
        'subject_id' => $subject_id,
        'chapter_from' => $chapter_from,
        'chapter_to' => $chapter_to,
        'paper_style' => $paper_stytle,
        'paper_time' => $paper_time,
        'no_mcqs' => $no_mcqs,
        'marks_mcq' => $marks_mcq,
        'no_short_questions' => $no_short_questions,
        'marks_short_question' => $marks_short_question,
        'no_long_questions' => $no_long_questions,
        'marks_long_question' => $marks_long_question,
        'lines_short_question' => $lines_short_question,
        'lines_long_question' => $lines_long_question
      ];

      Session::set('paper',$paper);

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

      if($data['paper_style']==2){
        return $this->view("papers/template/template_english", $data);
      }elseif ($data['paper_style']==3) {
        return $this->view("papers/template/template_urdu", $data);
      }
      elseif ($data['paper_style']==4) {
        return $this->view("papers/template/template_english_urdu", $data);
      }
      
  }

  public function save_generated($paper_id = null)
  {
    $paper = Session::get('paper');
  
    if ($paper_id) {
      $paperDB = PaperModel::where('paper_id', $paper_id)->first();
      $paperDB->id_subject = $paper['subject_id'];
      $paperDB->id_class = $paper['class_id'];
      $paperDB->id_exam_type = $paper['exam_type'];
      $paperDB->id_chapter_from = $paper['chapter_from'];
      $paperDB->id_chapter_to = $paper['chapter_to'];
      $paperDB->id_paper_style = $paper['paper_style'];
      $paperDB->paper_time = $paper['paper_time'];
      $paperDB->no_mcqs = $paper['no_mcqs'];
      $paperDB->marks_mcq = $paper['marks_mcq'];
      $paperDB->no_short_question = $paper['no_short_questions'];
      $paperDB->marks_short_question = $paper['marks_short_question'];
      $paperDB->no_long_question = $paper['no_long_questions'];
      $paperDB->marks_long_question = $paper['marks_long_question'];
      $paperDB->no_lines_short_question = $paper['lines_short_question'];
      $paperDB->no_lines_long_question = $paper['lines_long_question'];
      $paperDB->id_modify = Auth::user_id();
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
        'id_exam_type' => $paper['exam_type'],
        'id_class' => $paper['class_id'],
        'id_subject' => $paper['subject_id'],
        'id_chapter_from' => $paper['chapter_from'],
        'id_chapter_to' => $paper['chapter_to'],
        'id_paper_style' => $paper['paper_style'],
        'id_paper_style' => $paper['paper_style'],
        'paper_time' => $paper['paper_time'],
        'no_mcqs'=> $paper['no_mcqs'],
        'marks_mcq' => $paper['marks_mcq'],
        'no_short_question' => $paper['no_short_questions'],
        'marks_short_question' => $paper['marks_short_question'],
        'no_long_question' => $paper['no_long_questions'],
        'marks_long_question' => $paper['marks_long_question'],
        'no_lines_short_question' => $paper['lines_short_question'],
        'no_lines_long_question' => $paper['lines_long_question'], 
        'id_added' => Auth::user_id(),
        
      ]);
    }

    
    // MCQs Questions
    foreach ($paper['mcq_questions'] as $questionM) {

      PaperQuestions::create([
        'id_paper' => $newPaper->paper_id,
        'id_question' => $questionM['question_id']
      ]);

    }

    // Short Questions
    foreach ($paper['short_questions'] as $questionS) {

      PaperQuestions::create([
        'id_paper' => $newPaper->paper_id,
        'id_question' => $questionS['question_id']
      ]);

    }

     // Long Questions
     foreach ($paper['long_questions'] as $questionL) {

      PaperQuestions::create([
        'id_paper' => $newPaper->paper_id,
        'id_question' => $questionL['question_id']
      ]);

    }

    


    return redirect($this->route, [
      'type' => 'success',
      'message' => "Paper saved successfully"
    ]);
  }


  public function edit($paper_id)
  {

      $classes = ClassesModel::all();
      
      $paperTypes = ExamTypeModel::where('is_deleted','!=',1)
      ->where('type_status','=',1)
      ->get();

      $papers = PaperModel::all();

      $paperStyles = PaperStyle::where('id_deleted','=',NULL)
      ->where('paper_style_status','=',1)
      ->get();

     
      $paper = PaperModel::where('paper_id', $paper_id)
      ->where('id_deleted','=',NULL)
      ->first();

      $subjects = SubjectModel::where('id_class','=',$paper->id_class)->get();

      $subject_chapters = Chapter::where('id_subject','=',$paper->id_subject)->get();

      if (!$paper)
        throw  new AppError($this->route, "Paper does not exit with the given Id.");

      $data = [
        'classes' => $classes,
        'paper' => $paper,
        'class_subjects' => $subjects,
        'subject_chapters' => $subject_chapters,
        'papers' => $papers,
        'paper_id' => $paper_id,
        'paper_type' => $paperTypes,
        'paperStyles' => $paperStyles
      ];

      $this->view("papers/edit", $data);
  }

  public function delete($paper_id)
  {

    $paperDel = PaperModel::where('paper_id', $paper_id)->first();
    $paperDel->id_deleted = Auth::user_id();
    $paperDel->date_deleted = \Carbon\Carbon::now();
    $paperDel->save();
    

    return redirect($this->route, [
        'type' => 'success',
        'message' => "Paper deleted successfully"
    ]);
  }

  public function print($paper_id)
  {
      $paper = PaperModel::where('paper_id', $paper_id)
      ->with('class')
      ->with('exam_type')
      ->with('subject')
      ->first();

      if (!$paper)
          throw new AppError($this->route, "Invalid Paper Id is provided", 404);

      $paperQuestion = PaperQuestions::where('id_paper', $paper_id)
      ->with('question')
      ->get();
      
      $longQuestion = array();
      $shortQuestion = array();
      $mcqQuestion = array();

      foreach($paperQuestion as $qs){
        if($qs['question']['id_question_type'] == 1){
          array_push($longQuestion, $qs['question']);
        }elseif ($qs['question']['id_question_type'] == 2) {
          array_push($shortQuestion, $qs['question']);
        }else{
          $mcq_id = $qs['question']['question_id'];
          $obj_question = Question::where('question_id','=',$mcq_id)
          ->with('answers_options')
          ->first();
          array_push($mcqQuestion, $obj_question);
        }
      }
     

      $data = [
          'paper' => $paper,
          'longQuestion' => $longQuestion,
          'shortQuestion' => $shortQuestion,
          'mcqQuestion' => $mcqQuestion
      ];

      if($paper->id_paper_style == 2){
        $this->viewWithOutHeaderAndFooter("papers/print/print_english", $data);
      }elseif ($paper->id_paper_style == 3) {
        $this->viewWithOutHeaderAndFooter("papers/print/print_urdu", $data);
      }elseif ($paper->id_paper_style == 4) {
        $this->viewWithOutHeaderAndFooter("papers/print/print_english_urdu", $data);
      }
      
  }
  public function print_key($paper_id)
  {
      $paper = PaperModel::where('paper_id', $paper_id)
      ->with('class')
      ->with('exam_type')
      ->with('subject')
      ->first();

      if (!$paper)
          throw new AppError($this->route, "Invalid Paper Id is provided", 404);

      $paperQuestion = PaperQuestions::where('id_paper', $paper_id)
      ->get();
      
      $mcqQuestion = array();

      foreach($paperQuestion as $qs){
        if($qs->question->id_question_type == 3){
          $mcq_id = $qs->question->question_id;
          $obj_question = Question::where('question_id','=',$mcq_id)
          ->with('answers_options')
          ->first();
          array_push($mcqQuestion, $obj_question);;
        }
      }
     

      $data = [
          'paper' => $paper,
          'mcqQuestion' => $mcqQuestion
      ];

      if($paper->id_paper_style == 2){
        $this->viewWithOutHeaderAndFooter("papers/print_key/print_english", $data);
      }elseif ($paper->id_paper_style == 3) {
        $this->viewWithOutHeaderAndFooter("papers/print_key/print_urdu", $data);
      }elseif ($paper->id_paper_style == 4) {
        $this->viewWithOutHeaderAndFooter("papers/print_key/print_english_urdu", $data);
      }
      
  }


}
