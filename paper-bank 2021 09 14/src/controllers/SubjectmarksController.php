<?php

class SubjectMarks extends Controller {

  protected $route = "subjectmarks";

  public function index()
  {
    $marks = SubjectPapersMaxNumbers::all();

    $data = [
      'marks' => $marks
    ];

    return $this->view("subjectmarks/index", $data);
    
  }

  public function add()
  {
    $exam_types = PaperType::all();
    $subjects = SubjectModel::where('subject_status', true)->get();

    $data = [
      'exam_types' => $exam_types,
      'subjects' => $subjects
    ];

    return $this->view("subjectmarks/create", $data);
  }

  public function create()
  {
    $body = Request::body();

    if (!isset($body['subject_id']) || empty($body['subject_id']))
      throw new AppError($this->route, 'Please provide a valid subject');

    if (!isset($body['exam_type_id']) || empty($body['exam_type_id']))
      throw new AppError($this->route, 'Please provide a valid class');

    $alreadyExist = SubjectPapersMaxNumbers::where('id_subject', $body['subject_id'])
      ->where('id_exam_type', $body['exam_type_id'])->count();

    if ($alreadyExist >= 1){
      return redirect($this->route, [
        'type' => 'error',
        'message' => "The record already exist you can update the existing one"
      ]);
    }

    $user_id = Auth::user_id();
    $subjective_marks = $body['no_of_subjective'] * $_ENV['LONG_MARKS'];
    $objective_marks = $body['no_of_objective'] * $_ENV['MCQ_MARKS'];

    $subjects = SubjectPapersMaxNumbers::create([
      'subjective_marks' => $subjective_marks,
      'objective_marks' => $objective_marks,
      'max_numbers' => $subjective_marks + $objective_marks,
      'id_subject' => $body['subject_id'],
      'id_exam_type' => $body['exam_type_id'],
      'id_added' => $user_id
    ]);

    if (!$subjects)
      throw new AppError($this->route, 'Error saving your data, please reload and try again');

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Data saved successfully"
    ]);

  }

  public function edit($id)
  {
    $data = [];

    $subjectmarks = SubjectPapersMaxNumbers::where('id', $id)->first();

    if (!$subjectmarks || empty($subjectmarks))
      throw new AppError($this->route, "Invalid Id, No Record found");

    $data['subject'] = $subjectmarks->subject;
    $data['exam_type'] = $subjectmarks->exam;
    $data['marks'] = $subjectmarks;
    $data['no_of_subjective'] = $subjectmarks->subjective_marks / $_ENV['LONG_MARKS'];
    $data['no_of_objective'] = $subjectmarks->objective_marks / $_ENV['MCQ_MARKS'];
    $data['total'] = $subjectmarks->max_numbers;
    $data['id'] = $id;

    return $this->view("subjectmarks/edit", $data);
  }

  public function update($id)
  {
    $body = Request::body();

    if (!isset($body['subject_id']) || empty($body['subject_id']))
      throw new AppError($this->route, 'Please provide a valid subject');

    if (!isset($body['exam_type_id']) || empty($body['exam_type_id']))
      throw new AppError($this->route, 'Please provide a valid class');

    $user_id = Auth::user_id();
    $subjective_marks = $body['no_of_subjective'] * $_ENV['LONG_MARKS'];
    $objective_marks = $body['no_of_objective'] * $_ENV['MCQ_MARKS'];

    $subjects = SubjectPapersMaxNumbers::where('id', $id)->update([
      'subjective_marks' => $subjective_marks,
      'objective_marks' => $objective_marks,
      'max_numbers' => $objective_marks + $subjective_marks,
      'id_modify' => $user_id
    ]);

    if (!$subjects)
      throw new AppError($this->route, 'Error updating your data, please reload and try again');

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Data updated successfully"
    ]);
  }

  public function delete($id)
  {
    return $this->shadowDelete();

    $subjectmarks = SubjectPapersMaxNumbers::where('id', $id)->first();

    if (!$subjectmarks || empty($subjectmarks))
      throw new AppError($this->route, "Invalid Id, No Data found");

    $user_id = Auth::user_id();
    $user_ip = user_ip_address();

    $subjectmarks->id_deleted = $user_id;
    $subjectmarks->ip_deleted = $user_ip;
    $subjectmarks->save();
    $subjectmarks->delete();

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Record has been deleted successfully"
    ]);
  }

  public function shadowDelete(){
    return redirect($this->route, [
      'type' => 'error',
      'message' => "You are not allowed to delete this record"
    ]);
  }
}