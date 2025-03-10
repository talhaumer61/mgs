<?php

use \Carbon\Carbon;

class ExamType extends Controller {

  protected $route = 'examtype';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $exam_types = ExamTypeModel::orderBy('type_id', 'DESC')->get();

    $data = [
      'title' => 'Exam Types Panel',
      'exam_types' => $exam_types
    ];

    $this->view("exam_types/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['type_name'])){
      throw new AppError($this->route, "A Exam Type with the same name already exist");
    }

    $exam_type = ExamTypeModel::create([
      'type_name' => $body['type_name'],
      'type_status' => $body['type_status'],
      'id_added' => Auth::user_id()
    ]);

    if(!$exam_type)
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

    $exam_type = ExamTypeModel::find($id);

    if (!$exam_type)
      throw new AppError($this->route, "Invalid Id provided");

    $data = [
      'id' => $exam_type->type_id,
      'title' => 'Update Exam Type Record',
      'exam_type' => $exam_type
    ];

    $this->view("exam_types/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $body = Request::body();

    $exam_type = ExamTypeModel::where('type_id', $id)->first();

    if (!$exam_type)
      throw new AppError($this->route, "Invalid Id provided");

    

    $updatedExamType = ExamTypeModel::where('type_id', $id)->update([
      'type_name' => $body['type_name'],
      'type_status' => $body['type_status'],
      'id_modify' => Auth::user_id(),
      'date_modify' => Carbon::now()
    ]);

    if (!$updatedExamType){
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

    $exam_type = ExamTypeModel::find($id);

    if (!$exam_type)
      throw new AppError($this->route, "Invalid Id provided");

    $exam_type->id_deleted = Auth::user_id();
    $exam_type->date_deleted = Carbon::now();
    $exam_type->ip_deleted = user_ip_address();
    $exam_type->is_deleted = 1;
    $exam_type->save();

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

      $exam_type = ExamTypeModel::find($id);

      if (!$exam_type)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $exam_type->type_id,
        'title' => 'Update Exam Type Record',
        'exam_type' => $exam_type
      ];

      return $this->viewWithOutHeaderAndFooter("exam_types/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_unique_name($name){
    $exam_types = ExamTypeModel::where('type_name', $name)->get();
    if ($exam_types->count() > 0){
      return false;
    }

    return  true;
  }
}