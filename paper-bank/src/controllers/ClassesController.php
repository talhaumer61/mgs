<?php

use \Carbon\Carbon;

class Classes extends Controller {

  protected $route = 'classes';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $classes = ClassesModel::where('is_deleted','!=',1)->get();

    $data = [
      'title' => 'Classes Panel',
      'classes' => $classes
    ];

    $this->view("classes/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['class_name'])){
      throw new AppError($this->route, "A Class with the same name already exist");
    }

    $class = ClassesModel::create([
      'class_name' => $body['class_name'],
      'class_status' => $body['class_status'],
      'id_added' => Auth::user_id()
    ]);

    if(!$class)
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

    $class = ClassesModel::find($id);

    if (!$class)
      throw new AppError($this->route, "Invalid Id provided");

    $data = [
      'id' => $class->class_id,
      'title' => 'Update Class Record',
      'class' => $class
    ];

    $this->view("classes/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $body = Request::body();

    $class = ClassesModel::where('class_id', $id)->first();

    if (!$class)
      throw new AppError($this->route, "Invalid Id provided");

    if ($class->class_name != $body['class_name']){
      if (!$this->is_unique_name($body['class_name'])){
        throw new AppError($this->route, "A Class with the same name already exist");
      }
    }

    $updatedClass = ClassesModel::where('class_id', $id)->update([
      'class_name' => $body['class_name'],
      'class_status' => $body['class_status'],
      'id_modify' => Auth::user_id(),
      'date_modify' => Carbon::now()
    ]);

    if (!$updatedClass){
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

    $class = ClassesModel::find($id);

    if (!$class)
      throw new AppError($this->route, "Invalid Id provided");

    $class->id_deleted = Auth::user_id();
    $class->date_deleted = Carbon::now();
    $class->ip_deleted = user_ip_address();
    $class->save();

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

      $class = ClassesModel::find($id);

      if (!$class)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $class->class_id,
        'title' => 'Update Class Record',
        'class' => $class
      ];

      return $this->viewWithOutHeaderAndFooter("classes/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_unique_name($name){
    $classes = ClassesModel::where('class_name', $name)->get();
    if ($classes->count() > 0){
      return false;
    }

    return  true;
  }

  public function class_sections($class_id)
  {
    $sections = ClassesModel::where('class_id', $class_id)->first();
    if($sections){
      $sections = $sections->sections;
    }


    if (!$sections)
      throw new ApiError('No Record Found', 404);

    echo json_encode($sections);
  }

  public function class_subjects($class_id)
  {
    $subjects = SubjectModel::where('id_class', $class_id)
    ->where('subject_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();

    
    if (!$subjects)
      throw new ApiError('No Record Found', 404);

    echo json_encode($subjects);
  }

}