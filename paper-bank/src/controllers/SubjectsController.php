<?php

use \Carbon\Carbon;

class Subjects extends Controller {

  protected $route = 'subjects';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $subjects = SubjectModel::where('is_deleted','!=',1)
    ->whereHas('class', function ($query) {
      $query->where('is_deleted','!=',1);
    })->get();

    $classes = ClassesModel::where('is_deleted','!=',1)
    ->where('class_status','=',1)
    ->get();

    $data = [
      'title' => 'Subjects Panel',
      'subjects' => $subjects,
      'classes' => $classes
    ];

    $this->view("subjects/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_valid_class_id($body['id_class'])){
      throw new AppError($this->route, "Invalid class Id provided, Please select a valid class");
    }

    if (!$this->is_unique_name($body['subject_name'], $body['id_class'])){
      throw new AppError($this->route, "Subject Name already exist for the class");
    }

    $filename = upload("subject_img", "uploads", [
      'name_prefix' => $body['subject_name'],
      'max_size' => 10 * 1024 * 1024,
    ]);

    if (!$filename){
      throw  new AppError($this->route, "Error uploading images please try again");
    }

    $subject = SubjectModel::create([
      'subject_name' => $body['subject_name'],
      'subject_status' => $body['subject_status'],
      'subject_img' => $filename,
      'id_class' => $body['id_class'],
      'id_added' => Auth::user_id()
    ]);

    if(!$subject)
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
      throw new AppError($this->route, "Invalid Subject Id provided");

    $subject = SubjectModel::find($id);

    if (!$subject)
      throw new AppError($this->route, "Invalid Subject Id provided");

    $data = [
      'id' => $subject->subject_id,
      'title' => 'Update Subject Record',
      'subject' => $subject
    ];

    $this->view("subjects/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Subject Id provided");

    $body = Request::body();

    if (!$this->is_valid_class_id($body['id_class'])){
      throw new AppError($this->route, "Invalid class Id provided, Please select a valid class");
    }

    $subject = SubjectModel::find($id);

    if (!$subject)
      throw new AppError($this->route, "Invalid Subject Id provided");

    if ($subject->subject_name !== $body['subject_name']){
      if (!$this->is_unique_name($body['subject_name'], $body['id_class'])){
        throw new AppError($this->route, "Subject Name already exist for the class");
      }
    }

    $update_array = [
      'subject_name' => $body['subject_name'],
      'subject_status' => $body['subject_status'],
      'id_class' => $body['id_class'],
    ];

    

    $updated_subject = SubjectModel::where('subject_id', $id)->update($update_array);

    if (!$updated_subject){
      throw new AppError($this->route, "Error Updating the record.");
    }

    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Data Updated successfully'
    ]);
  }

  public function delete($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Subject Id provided");

    $subject = SubjectModel::find($id);

    if (!$subject)
      throw new AppError($this->route, "Invalid Subject Id provided");

    $subject->id_deleted = Auth::user_id();
    $subject->date_deleted = Carbon::now();
    $subject->ip_deleted = user_ip_address();
    $subject->save();

    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Record Deleted successfully'
    ]);
  }

  public function is_unique_name($name, $class_id){
    $classes = SubjectModel::where('subject_name', $name)->where('id_class', $class_id)->get();
    if ($classes->count() > 0){
      return false;
    }

    return  true;
  }

  public function edit_html($id = null)
  {
    try {
      if (!$id)
        throw new Exception("Invalid Id");

      $subject = SubjectModel::find($id);
      $classes = ClassesModel::where('is_deleted','!=',1)
      ->where('class_status','=',1)
      ->get();

      if (!$subject)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $subject->subject_id,
        'title' => 'Update Subject Record',
        'subject' => $subject,
        'classes' => $classes,
        'id_class' => $subject->id_class
      ];

      return $this->viewWithOutHeaderAndFooter("subjects/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_valid_class_id($id){
    $class = ClassesModel::find($id);
    if (!$class)
      return false;

    return true;
  }

  public function subject_chapters($subject_id)
  {
    $chapters = Chapter::where('id_subject','=', $subject_id)
    ->where('id_deleted','=', NULL)
    ->get();

    

    if (!$chapters)
      throw new ApiError('No Record Found', 404);

    echo json_encode($chapters);
  }
}