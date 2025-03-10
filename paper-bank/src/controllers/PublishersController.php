<?php

use \Carbon\Carbon;

class Publishers extends Controller {

  protected $route = 'publishers';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $publishers = PublisherModel::all();

    $data = [
      'title' => 'Publisher Panel',
      'publishers' => $publishers
    ];

    $this->view("publisher/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['publisher_name'])){
      throw new AppError($this->route, "A Publisher with the same name already exist");
    }

    $publisher = PublisherModel::create([
      'publisher_name' => $body['publisher_name'],
      'publisher_status' => $body['publisher_status'],
      'id_added' => Auth::user_id()
    ]);

    if(!$publisher)
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

    $publisher = PublisherModel::find($id);

    if (!$publisher)
      throw new AppError($this->route, "Invalid Id provided");

    $data = [
      'id' => $publisher->publisher_id,
      'title' => 'Update Publisher Record',
      'publisher' => $publisher
    ];

    $this->view("publisher/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Id provided");

    $body = Request::body();

    $publisher = PublisherModel::where('publisher_id', $id)->first();

    if (!$publisher)
      throw new AppError($this->route, "Invalid Id provided");

    if ($publisher->publisher_name != $body['publisher_name']){
      if (!$this->is_unique_name($body['publisher_name'])){
        throw new AppError($this->route, "A Publisher with the same name already exist");
      }
    }

    $updatedQuestionType = PublisherModel::where('publisher_id', $id)->update([
      'publisher_name' => $body['publisher_name'],
      'publisher_status' => $body['publisher_status'],
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

    $publisher = PublisherModel::find($id);

    if (!$publisher)
      throw new AppError($this->route, "Invalid Id provided");

    $publisher->id_deleted = Auth::user_id();
    $publisher->date_deleted = Carbon::now();
    $publisher->ip_deleted = user_ip_address();
    $publisher->save();

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

      $publisher = PublisherModel::find($id);

      if (!$publisher)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $publisher->publisher_id,
        'title' => 'Update Publisher Record',
        'publisher' => $publisher
      ];

      return $this->viewWithOutHeaderAndFooter("publisher/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_unique_name($name){
    $publisher = PublisherModel::where('publisher_name', $name)->get();
    if ($publisher->count() > 0){
      return false;
    }

    return  true;
  }
}