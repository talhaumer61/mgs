<?php

use \Carbon\Carbon;

class QuestionType extends Controller {

  protected $route = 'questiontype';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $question_types = QuestionTypeModel::all();

    $data = [
      'title' => 'Question Types Panel',
      'question_types' => $question_types
    ];

    $this->view("question_type/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['question_type_name'])){
      throw new AppError($this->route, "A Question Type with the same name already exist");
    }

    $question_type = QuestionTypeModel::create([
      'question_type_name' => $body['question_type_name'],
      'question_type_status' => $body['question_type_status'],
      'id_added' => Auth::user_id()
    ]);

    if(!$question_type)
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

    $question_type = QuestionTypeModel::find($id);

    if (!$question_type)
      throw new AppError($this->route, "Invalid Id provided");

    $data = [
      'id' => $question_type->question_type_id,
      'title' => 'Update Question Type Record',
      'question_type' => $question_type
    ];

    $this->view("question_type/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $body = Request::body();

    $question_type = QuestionTypeModel::where('question_type_id', $id)->first();

    if (!$question_type)
      throw new AppError($this->route, "Invalid Id provided");

    if ($question_type->question_type_name != $body['question_type_name']){
      if (!$this->is_unique_name($body['question_type_name'])){
        throw new AppError($this->route, "A Question Type with the same name already exist");
      }
    }

    $updatedQuestionType = QuestionTypeModel::where('question_type_id', $id)->update([
      'question_type_name' => $body['question_type_name'],
      'question_type_status' => $body['question_type_status'],
      'id_modify' => Auth::user_id(),
      'date_modify' => Carbon::now()
    ]);

    if (!$updatedQuestionType){
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

    $question_type = QuestionTypeModel::find($id);

    if (!$question_type)
      throw new AppError($this->route, "Invalid Id provided");

    $question_type->id_deleted = Auth::user_id();
    $question_type->date_deleted = Carbon::now();
    $question_type->ip_deleted = user_ip_address();
    $question_type->save();

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

      $question_type = QuestionTypeModel::find($id);

      if (!$question_type)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $question_type->question_type_id,
        'title' => 'Update Question Type Record',
        'question_type' => $question_type
      ];

      return $this->viewWithOutHeaderAndFooter("question_type/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_unique_name($name){
    $question_type = QuestionTypeModel::where('question_type_name', $name)->get();
    if ($question_type->count() > 0){
      return false;
    }

    return  true;
  }
}