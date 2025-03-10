<?php

use \Carbon\Carbon;

class Topics extends Controller {

  protected $route = 'topics';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $topics = TopicModel::where('id_deleted','=', Null)
    ->get();
    $classes = ClassesModel::activeClasses();

    $data = [
      'title' => 'Topics Panel',
      'topics' => $topics,
      'classes' => $classes
    ];

    $this->view("topic/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['topic_name'])){
      return redirect($this->route, [
        'type' => 'error',
        'message' => "A Topic with the same name already exist"
      ]);
    }

    $topic = TopicModel::create([
      'topic_name' => $body['topic_name'],
      'topic_status' => $body['topic_status'],
      'id_subject' => $body['id_subject'],
      'id_class' => $body['id_class'],
      'id_chapter' => $body['id_chapter'],
      'id_added' => Auth::user_id()
    ]);

    if(!$topic)
      throw new AppError($this->route, "Error creating new record. please try again");

    // Everything went well redirect the user
    return redirect($this->route, [
      'type' => 'success',
      'message' => "Record created successfully"
    ]);
  }

  public function edit($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $topic = TopicModel::find($id);
    $classes = ClassesModel::activeClasses();

    if (!$topic)
      throw new AppError($this->route, "Invalid Id provided");

    $data = [
      'id' => $topic->topic_id,
      'title' => 'Update Topic Record',
      'topic' => $topic,
      'classes' => $classes
    ];

    $this->view("topic/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $body = Request::body();

    $topic = TopicModel::where('topic_id', $id)->first();

    if (!$topic)
      throw new AppError($this->route, "Invalid Id provided");

    if ($topic->topic_name != $body['topic_name']){
      if (!$this->is_unique_name($body['topic_name'])){
        throw new AppError($this->route, "A Topic with the same name already exist");
      }
    }

    $updated_topic = TopicModel::where('topic_id', $id)->update([
      'topic_name' => $body['topic_name'],
      'topic_status' => $body['topic_status'],
      'id_subject' => $body['id_subject'],
      'id_class' => $body['id_class'],
      'id_chapter' => $body['id_chapter'],
      'id_modify' => Auth::user_id(),
      'date_modify' => Carbon::now()
    ]);

    if (!$updated_topic){
      throw new AppError($this->route, "Error updating the record");
    }

    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Data Updated successfully'
    ]);
  }

  public function delete($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $topic = TopicModel::find($id);

    if (!$topic)
      throw new AppError($this->route, "Invalid Id provided");

    $topic->id_deleted = Auth::user_id();
    $topic->date_deleted = Carbon::now();
    $topic->ip_deleted = user_ip_address();
    $topic->save();

    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Record Deleted successfully'
    ]);
  }

  public function edit_html($id = null)
  {
    try {
      if (!$id)
        throw new Exception("Invalid Id");

      $topic = TopicModel::find($id);
      $topic_subjects = SubjectModel::where('subject_status', true)->where('id_class', $topic->id_class)->get();
      $topic_chapters = Chapter::where('id_subject', $topic->id_subject)->get();
      $classes = ClassesModel::activeClasses();


      if (!$topic)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $topic->topic_id,
        'title' => 'Update Topic Record',
        'topic' => $topic,
        'classes' => $classes,
        'topic_subjects' => $topic_subjects,
        'id_subject' => $topic->id_subject,
        'id_class' => $topic->id_class,
        'id_chapter' => $topic->id_chapter,
        'topic_chapters' => $topic_chapters
      ];

      return $this->viewWithOutHeaderAndFooter("topic/edit_json", $data);
    }catch (Exception $ex){
      die($ex);
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }
  }

  public function is_unique_name($name){
    $topic = TopicModel::where('topic_name', $name)
    ->where('id_deleted','=',NULL)
    ->get();
    if ($topic->count() > 0){
      return false;
    }

    return  true;
  }

  public function class_sections($topic_id)
  {
    $sections = TopicModel::where('topic_id', $topic_id)->first();
    if($sections){
      $sections = $sections->sections;
    }


    if (!$sections)
      throw new ApiError('No Record Found', 404);

    echo json_encode($sections);
  }

  public function subject_topics($topic_id)
  {
    $subjects = TopicModel::where('topic_id', $topic_id)->first();
    if($subjects){
      $subjects = $subjects->subjects;

    }

    if (!$subjects)
      throw new ApiError('No Record Found', 404);

    echo json_encode($subjects);
  }

}