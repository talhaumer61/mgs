<?php

class Questions  extends Controller
{

  public function __construct(){
    $this->authMiddleware();
  }

  protected $route = 'questions';

  public function index()
  {
    
    
    $classes = ClassesModel::where('class_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();
    $question_types = QuestionTypeModel::activeTypes();

    $data = [
      'classes' => $classes,
      'question_types' => $question_types
    ];

    $this->view("questions/index", $data);
  }

  public function add(){
    $question_type_id = Request::query('question_type_id');

    if(empty($question_type_id) || !isset($question_type_id)){
      return redirect($this->route, [
        'type' => 'error',
        'message' => "Please select a valid question type"
      ]);
    }

    $question_type = QuestionTypeModel::find($question_type_id);

    if (!$question_type){
      return  redirect($this->route, [
        'type' => 'error',
        'message' => "Invalid Question Type Provided"
      ]);
    }

    $layout = getQuestionLayout($question_type_id);

    $title = [
      '1' => 'Add New Long Question',
      '2' => 'Add New Short Question',
      '3' => 'Add New MCQs ',
    ];

    
    $classes  = ClassesModel::where('is_deleted','!=',1)
    ->where('class_status','=',1)
    ->get();
    
    $publishers = PublisherModel::where('id_deleted','=',NULL)
    ->where('publisher_status','=',1)
    ->get();
    $boards = BoardModel::where('id_deleted','=',NULL)
    ->where('board_status','=',1)
    ->get();

    $data = [
      'classes' => $classes,
      'boards' => $boards,
      'publishers' => $publishers,
      'layout' => $layout,
      'title' => $title[$question_type_id]
    ];

    $this->view("questions/create", $data);
  }

  public function get_question($question_id){

    $question = Question::find($question_id);
    // print_r($question['id_question_type']) ;
    if(isset($question['id_question_type']) && $question['id_question_type'] == "3"){

      $matchThese = ['id_question' => $question_id];
      $obj_answer = ObjectiveAnswers::where($matchThese)->first();
      $data = [
        'question' => $question,
        'obj_answer' => $obj_answer
      ];
    }
    elseif (isset($question['id_question_type']) && $question['id_question_type'] == "1" || $question['id_question_type'] == "2") {
      $data = [
        'question' => $question
      ];
    }

    $data['question']['question_english']=html_entity_decode($question['question_english']);
    $data['question']['question_urdu']=html_entity_decode($question['question_urdu']);

    if(!$data['question']){
      $data['status'] = false;
    }
    $data['status'] = true;
    
    
    echo json_encode($data);
  }

  

  public function edit($id){

    $question = Question::where('question_id', $id)
    ->with('answers_options')
    ->first();
    
    $chapters = Chapter::where('id_subject', $question->id_subject)->get();
    $subjects = SubjectModel::where('id_class', $question->id_class)->get();
    $topics = TopicModel::where('id_chapter', $question->id_chapter)->get();

    $classes = ClassesModel::where('class_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();


    
    $publishers = PublisherModel::where('id_deleted','=',NULL)
    ->where('publisher_status','=',1)
    ->get();
    $boards = BoardModel::where('id_deleted','=',NULL)
    ->where('board_status','=',1)
    ->get();
    
    $layout = getQuestionLayout($question->id_question_type);

    $title = [
      '1' => 'Edit Long Question',
      '2' => 'Edit Short Question',
      '3' => 'Edit MCQs ',
    ];

    $data = [
      'title' => $title[$question->id_question_type],
      'question_id' => $question->question_id,
      'question' => $question,
      'boards' => $boards,
      'publishers' => $publishers,
      'classes' => $classes,
      'layout' => $layout,
      'topics' => $topics,
      'subjects' => $subjects,
      'chapters' => $chapters
    ];

    $this->view("questions/edit", $data);
  }

  public function delete_react($id){
    $question = Question::find($id);

    if (!$question){
      echo 'false';
    }

    $question->id_deleted = Auth::user_id();
    $question->date_deleted = \Carbon\Carbon::now();
    $question->ip_deleted = user_ip_address();
    $question->save();

    echo 'true';
  }

  public function delete($id){
    $body = Request::body();
    $question = Question::find($id);

    

    if (!$question){
      return redirect($this->route, [
        'type' => 'Error',
        'message' => "Deleting Record"
     ]);
  
    }

    $question->id_deleted = Auth::user_id();
    $question->date_deleted = \Carbon\Carbon::now();
    $question->ip_deleted = user_ip_address();
    $question->save();

    $s_id = $body['id_subject'];
    $cls_id = $body['id_class'];


    $this->filter_questions($cls_id,$s_id);
  }

  public function update($id){

    $body = Request::body();

    

    if (isset($body['question_id']) && ($body['id_question_type'] == "1" || $body['id_question_type'] == "2"))
    {
      $q_id = $id;
      // Update Question
      Question::where('question_id',$q_id)->update([
        'question_id' => $q_id,
        'id_board' => $body['id_board'],
        'id_publisher' => $body['id_publisher'],
        'id_subject' => $body['id_subject'],
        'id_class' => $body['id_class'],
        'id_chapter' => $body['id_chapter'],
        'id_topic' => $body['id_topic'],
        'question_urdu' => htmlentities($body['question_urdu']),
        'question_english' => htmlentities($body['question_english']),
        'id_modify' => Auth::user_id(),
        'date_modify' => \Carbon\Carbon::now(),
        'page_num' => $body['page_num'],
        'id_question_type' => $body['id_question_type']
      ]);
      
    }elseif (isset($body['question_id']) && $body['id_question_type'] == "3" ) 
    {
      $q_id = $body['question_id'];
      $matchThese1 = ['question_id' => $q_id];
      $matchThese2 = ['id_question' => $q_id];

      // Question Update Question
      Question::where($matchThese1)->update([
        'id_board' => $body['id_board'],
        'id_publisher' => $body['id_publisher'],
        'id_subject' => $body['id_subject'],
        'id_class' => $body['id_class'],
        'id_chapter' => $body['id_chapter'],
        'id_topic' => $body['id_topic'],
        'question_urdu' => htmlentities($body['question_urdu']),
        'question_english' => htmlentities($body['question_english']),
        'id_modify' => Auth::user_id(),
        'date_modify' => \Carbon\Carbon::now(),
        'page_num' => $body['page_num'],
        'id_question_type' => $body['id_question_type']
      ]);
      
        // Update Objective Answer
      $objAnswer = ObjectiveAnswers::where($matchThese2);
      $objAnswer->update([

        'id_question' => $q_id,
        'e_option_a' => $body['e_option_a'],
        'e_option_b' => $body['e_option_b'],
        'e_option_c' => $body['e_option_c'],
        'e_option_d' => $body['e_option_d'],
        'e_option_correct' => $body['e_option_correct'],
        'u_option_a' => $body['u_option_a'],
        'u_option_b' => $body['u_option_b'],
        'u_option_c' => $body['u_option_c'],
        'u_option_d' => $body['u_option_d'],
        'u_option_correct' => $body['u_option_correct']
      ]);
      
    }
    $s_id = $body['id_subject'];
    $cls_id = $body['id_class'];
    
    $this->filter_questions($cls_id,$s_id);

  }

  public function question_type(){
    $question_types = QuestionTypeModel::where("question_type_status", true)->get();

    $data = [
      'title' => "Select a Question Type",
      'question_types' => $question_types
    ];

    $this->view("questions/questionType", $data);
  }


  public function filter_questions($cls_id=null, $s_id=null){

    $body = Request::body();

    if(!isset($body['id_class']) || (isset($body['id_subject']) && $body['id_subject'] == -1) ){
      return redirect($this->route, [
        'type' => 'error',
        'message' => "Please Select Class and Subject atleast"
      ]);
    }else{

        $classes = ClassesModel::where('class_status','=',1)
        ->where('is_deleted','!=',1)
        ->get();
        $subjects = SubjectModel::where('id_class','=',$body['id_class'])->get();
        $subject_chapters = Chapter::where('id_subject','=',$body['id_subject'])->get();
        $question_types = QuestionTypeModel::activeTypes();

        if((isset($body['id_chapter']) && $body['id_chapter'] == -1) && (isset($body['id_chapter_to']) && $body['id_chapter_to'] == -1)){

          $long_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 1)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $short_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 2)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $obj_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 3)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->with('answers_options')
          ->get();
        }elseif ((isset($body['id_chapter']) && $body['id_chapter'] != -1) && (isset($body['id_chapter_to']) && $body['id_chapter_to'] != -1)) {
          

          $long_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '>=', $body['id_chapter'])
          ->where('id_chapter', '<=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 1)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $short_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '>=', $body['id_chapter'])
          ->where('id_chapter', '<=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 2)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $obj_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '>=', $body['id_chapter'])
          ->where('id_chapter', '<=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 3)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->with('answers_options')
          ->get();

        }elseif ((isset($body['id_chapter']) && $body['id_chapter'] != -1) && (isset($body['id_chapter_to']) && $body['id_chapter_to'] == -1)) {

          $long_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter'])
          ->where('id_question_type', '=', 1)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $short_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter'])
          ->where('id_question_type', '=', 2)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $obj_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter'])
          ->where('id_question_type', '=', 3)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->with('answers_options')
          ->get();

        }elseif ((isset($body['id_chapter']) && $body['id_chapter'] == -1) && (isset($body['id_chapter_to']) && $body['id_chapter_to'] != -1)) {
          $long_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 1)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $short_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 2)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $obj_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_chapter', '=', $body['id_chapter_to'])
          ->where('id_question_type', '=', 3)
          ->where('id_deleted','=',NULL)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->with('answers_options')
          ->get();
        }elseif ($cls_id!=null && $s_id!=null) {
          $long_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 1)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $short_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 2)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->get();

          $obj_question = Question::where('id_class','=',$body['id_class'])
          ->where('id_subject', '=', $body['id_subject'])
          ->where('id_deleted','=',NULL)
          ->where('id_question_type', '=', 3)
          ->with('class')
          ->with('subject')
          ->with('chapter')
          ->with('answers_options')
          ->get();
        }

        $data = [
                'id_class' => $body['id_class'],
                'id_subject' => isset($body['id_subject']) ? $body['id_subject'] : null,
                'id_chapter' => isset($body['id_chapter']) ? $body['id_chapter'] : null,
                'id_chapter_to' => isset($body['id_chapter_to']) ? $body['id_chapter_to'] : null,
                'classes' => $classes,
                'question_types' => $question_types,
                'class_subjects' => $subjects,
                'subject_chapters' => $subject_chapters,
                'layout' => 'filtered_data',
                'long_question' => $long_question,
                'short_question' => $short_question,
                'obj_question' => $obj_question,
              ];
        
              $this->view("questions/index", $data);
      
    }
    
  }


  public function save_question(){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $body = json_decode(file_get_contents("php://input"));
    
    if (isset($body->question_id) && $body->question_id > 0 && ($body->id_question_type == "1" || $body->id_question_type == "2"))
    {
      $q_id = $body->question_id;
      // Update Question
      Question::where('question_id',$q_id)->update([
        'question_id' => $q_id,
        'id_board' => $body->id_board,
        'id_publisher' => $body->id_publisher,
        'id_subject' => $body->id_subject,
        'id_class' => $body->id_class,
        'id_chapter' => $body->id_chapter,
        'id_topic' => $body->id_topic,
        'question_urdu' => htmlentities($body->question_urdu),
        'question_english' => htmlentities($body->question_english),
        'id_modify' => Auth::user_id(),
        'date_modify' => \Carbon\Carbon::now(),
        'page_num' => $body->page_num,
        'id_question_type' => $body->id_question_type
      ]);
      $question = Question::where(['question_id' => $q_id])->first();
      $data =[
        'question' => $question
      ];
    }
    elseif (isset($body->question_id) && $body->question_id > 0 && $body->id_question_type == "3" ) 
    {
      $q_id = $body->question_id;
      $matchThese1 = ['question_id' => $q_id];
      $matchThese2 = ['id_question' => $q_id];

      // Question Update Question
      Question::where($matchThese1)->update([
        'id_board' => $body->id_board,
        'id_publisher' => $body->id_publisher,
        'id_subject' => $body->id_subject,
        'id_class' => $body->id_class,
        'id_chapter' => $body->id_chapter,
        'id_topic' => $body->id_topic,
        'question_urdu' => htmlentities($body->question_urdu),
        'question_english' => htmlentities($body->question_english),
        'id_modify' => Auth::user_id(),
        'date_modify' => \Carbon\Carbon::now(),
        'page_num' => $body->page_num,
        'id_question_type' => $body->id_question_type
      ]);
      
        // Update Objective Answer
      $objAnswer = ObjectiveAnswers::where($matchThese2);
      $objAnswer->update([

        'id_question' => $q_id,
        'e_option_a' => $body->e_option_a,
        'e_option_b' => $body->e_option_b,
        'e_option_c' => $body->e_option_c,
        'e_option_d' => $body->e_option_d,
        'e_option_correct' => $body->e_option_correct,
        'u_option_a' => $body->u_option_a,
        'u_option_b' => $body->u_option_b,
        'u_option_c' => $body->u_option_c,
        'u_option_d' => $body->u_option_d,
        'u_option_correct' => $body->u_option_correct
      ]);

      
      $question = Question::where($matchThese1)->first();
      $obj_answer = ObjectiveAnswers::where($matchThese2)->first();
      $data =[
        'question' => $question,
        'obj_answer' => $obj_answer
      ];
    }
    elseif (isset($body->id_question_type) && $body->question_id == '0' && ($body->id_question_type == "1" || $body->id_question_type == "2"))
    {
      //Add Question
      $question=Question::create([
        'id_board' => $body->id_board,
        'id_publisher' => $body->id_publisher,
        'id_subject' => $body->id_subject,
        'id_class' => $body->id_class,
        'id_chapter' => $body->id_chapter,
        'id_topic' => $body->id_topic,
        'question_urdu' => htmlentities($body->question_urdu),
        'question_english' => htmlentities($body->question_english),
        'id_added' => Auth::user_id(),
        'page_num' => $body->page_num,
        'id_question_type' => $body->id_question_type
      ]);
      $data = [
        'question' => $question
      ];
    }elseif (isset($body->id_question_type) && $body->id_question_type == "3" && $body->question_id == '0') {
      // Add Question
      $question=Question::create([
        'id_board' => $body->id_board,
        'id_publisher' => $body->id_publisher,
        'id_subject' => $body->id_subject,
        'id_class' => $body->id_class,
        'id_chapter' => $body->id_chapter,
        'id_topic' => $body->id_topic,
        'question_urdu' => htmlentities($body->question_urdu),
        'question_english' => htmlentities($body->question_english),
        'id_added' => Auth::user_id(),
        'page_num' => $body->page_num,
        'id_question_type' => $body->id_question_type
      ]);
      // Add OBjective Answer
        $obj_answer=ObjectiveAnswers::create([
        'id_question' => $question->question_id,
        'e_option_a' => $body->e_option_a,
        'e_option_b' => $body->e_option_b,
        'e_option_c' => $body->e_option_c,
        'e_option_d' => $body->e_option_d,
        'e_option_correct' => $body->e_option_correct,
        'u_option_a' => $body->u_option_a,
        'u_option_b' => $body->u_option_b,
        'u_option_c' => $body->u_option_c,
        'u_option_d' => $body->u_option_d,
        'u_option_correct' => $body->u_option_correct
      ]);
      $data =[
        'question' => $question,
        'obj_answer' => $obj_answer
      ];
    }

    

    if (!isset($data['question']) && !isset($data['obj_answer'])){
      echo "Error";
    }
    else{
      $data['question']['question_english']=html_entity_decode($data['question']['question_english']);
      $data['question']['question_urdu']=html_entity_decode($data['question']['question_urdu']);
      echo json_encode($data);
    }
  
  }

  public function delete_html($id = null)
  {
    try {
      if (!$id)
        throw new Exception("Invalid Id");

      $question = Question::find($id);

      if (!$question)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $question->question_id,
        'id_class' => $question->id_class,
        'id_subject' => $question->id_subject,
        'title' => 'Delete a Question'
      ];

    return $this->viewWithOutHeaderAndFooter("questions/delete_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

}
