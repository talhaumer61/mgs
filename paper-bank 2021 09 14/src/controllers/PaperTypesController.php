<?php


class PaperTypes  extends Controller
{

    public function __construct()
    {
        $this->authMiddleware();
    }

  protected $route = 'papertypes';

  public function index()
  {
    $data = PaperType::all();


    $data = [
      'paper_types' => $data,
    ];

    $this->view("papers/category", $data);
  }

  public function add()
  {
    $this->view("papers/create_category");
  }

  public function create()
  {
    $body = Request::body();

    $isSaved = PaperType::create([
      'paper_type' => $body['paper_type']
    ]);

    if (!$isSaved)
      throw new AppError($this->route, "Error creating the record, Please make sure you are providing valid informatiion", 400);

    return redirect($this->route, [
      'type' => 'success',
      'message' => "Record inserted successfully"
    ]);
  }

  public function edit($id)
  {

    $papertype = PaperType::where('paper_type_id', $id)->first();

    if (!$papertype)
      throw new AppError($this->route, "No record found for the givin Id", 404);

    $data = [
      'paper_type' => $papertype,
      'id' => $id
    ];

    $this->view('papers/edit_category', $data);
  }

  public function delete($id)
  {
    PaperType::where('paper_type_id', $id)->delete();
    return redirect($this->route, [
      'type' => 'success',
      'message' => 'Record Deleted successfully'
    ]);
  }

  public function update($id)
  {
    $body = Request::body();

    $papertype = PaperType::where('paper_type_id', $id)->update([
      'paper_type' => $body['paper_type']
    ]);

    if (!$papertype)
      throw new AppError($this->route, "Invalid student Id", 400);

    return redirect($this->route, ['type' => 'success', 'message' => "Record updated successfully"]);
  }

  // Get all the students of a particular class
  public function get_subjects_by_class()
  {

    if (!Request::body('id_class')) {
      throw new AppError($this->route, "Please provide a valid class name", 400);
    }

    $class_id = Request::body('id_class');
    $subjects = SubjectModel::where('class_id', $class_id)->get();
    $classes = ClassesModel::all();


    foreach ($subjects as $subject) {
      $class = $subject->class;
      $subject['class_name'] = $class['name'];
    }

    $data = [
      'subjects' => $subjects,
      'classes' => $classes
    ];

    $this->view("subjects/index", $data);
  }
}
