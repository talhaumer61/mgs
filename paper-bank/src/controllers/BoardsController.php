<?php

use \Carbon\Carbon;

class Boards extends Controller {

  protected $route = 'boards';

  public function __construct()
  {
    $this->authMiddleware();
  }

  public function index()
  {
    // Get the Data
    $boards = BoardModel::all();

    $data = [
      'title' => 'Boards Panel',
      'boards' => $boards
    ];

    $this->view("boards/index", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!$this->is_unique_name($body['board_name'])){
      throw new AppError($this->route, "A Board with the same name already exist");
    }

    $board = BoardModel::create([
      'board_name' => $body['board_name'],
      'board_status' => $body['board_status'],
      'id_added' => Auth::user_id()
    ]);

    if(!$board)
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
      throw new AppError($this->route, "Invalid Board Id provided");

    $board = BoardModel::find($id);

    if (!$board)
      throw new AppError($this->route, "Invalid Board Id provided");

    $data = [
      'id' => $board->board_id,
      'title' => 'Update Board Record',
      'board' => $board
    ];

    $this->view("boards/edit", $data);
  }

  public function update($id = null)
  {
    if (!$id)
      throw new AppError($this->route, "Invalid Board Id provided");

    $body = Request::body();

    $board = BoardModel::where('board_id', $id)->first();

    if (!$board)
      throw new AppError($this->route, "Invalid Board Id provided");

    if ($board->board_name != $body['board_name']){
      if (!$this->is_unique_name($body['board_name'])){
        throw new AppError($this->route, "A Board with the same name already exist");
      }
    }

    $updateBoard = BoardModel::where('board_id', $id)->update([
      'board_name' => $body['board_name'],
      'board_status' => $body['board_status'],
      'id_modify' => Auth::user_id(),
      'date_modify' => Carbon::now()
    ]);

    if (!$updateBoard){
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
      throw new AppError($this->route, "Invalid Board Id provided");

    $board = BoardModel::find($id);

    if (!$board)
      throw new AppError($this->route, "Invalid Board Id provided");

    $board->id_deleted = Auth::user_id();
    $board->date_deleted = Carbon::now();
    $board->ip_deleted = user_ip_address();
    $board->save();

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

      $board = BoardModel::find($id);

      if (!$board)
        throw new Exception("Invalid Id");

      $data = [
        'id' => $board->board_id,
        'title' => 'Update Board Record',
        'board' => $board
      ];

      return $this->viewWithOutHeaderAndFooter("boards/edit_json", $data);
    }catch (Exception $ex){
      http_response_code(201);
      echo json_encode([
        'type' => 'error',
        'message' => 'Invalid Id'
      ]);
    }

  }

  public function is_unique_name($name){
    $classes = BoardModel::where('board_name', $name)->get();
    if ($classes->count() > 0){
      return false;
    }

    return  true;
  }
}