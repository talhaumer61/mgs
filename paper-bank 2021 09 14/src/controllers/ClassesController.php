<?php

class Classes extends Controller
{
    protected $route = "classes";

    public function __construct()
    {
       $this->authMiddleware();
    }

    public function index()
    {
        $classes = ClassesModel::all();
        $section = SectionModel::all();

        $data = [
            'primary_key' => "class_id",
            'table_header' => ["Name"],
            'controller' => "classes",
            'sections' => $section,
            "data" => $classes,
            "show" => ['name']
        ];

        $this->view("classes/index", $data);
    }

    public function add()
    {
        $section = SectionModel::all();
        $data['sections'] = $section;
        $this->view("classes/create", $data);
    }

    /**
     * @throws AppError
     */
    public function edit($id)
    {
        $classModel = new ClassesModel();

        $class = $classModel->where('class_id', $id)->first();

        if (!$class)
            throw new AppError($this->route, 'Invalid Class Id', 404);

        $sections = SectionModel::all();

        $classesSection = $class->sections;

        for ($i = 0; $i < sizeof($sections); $i++)
            for ($j = 0; $j < sizeof($classesSection); $j++)
                if (isset($classesSection[$j]))
                    if ($sections[$i]['section_id'] == $classesSection[$j]['section_id'])
                        $sections[$i]['isSelected'] = true;


        $this->view("classes/edit", [
            'class_name' => $class['name'],
            'sections' => $sections,
            'classesSection' => $classesSection,
            'id' => $id
        ]);

        $this->view("classes/edit");
    }

    public function create()
    {

        $class = ClassesModel::create([
            'name' => $_POST['class_name']
        ]);

        $sections = [];

        foreach ($_POST['section_id'] as $section_id) {
            array_push($sections, [
                'class_id' => $class['class_id'],
                'section_id' => $section_id
            ]);
        }

        ClassesSectionModel::insert($sections);

        return redirect("classes", [
            'type' => 'success',
            'message' => "New Record created successfully..."
        ]);
    }

    /**
     * @throws AppError
     */
    public function update($id)
    {

        $class = ClassesModel::where("class_id", $id)->first();

        // Data Not Found
        if (!$class)
            throw new AppError($this->route, 'Invalid class Id', 404);

        // Class Name is not empty
        if (!Request::body('class_name'))
            throw new AppError($this->route . "/edit/" . $id, 'Please provide a valid class name', 402);

        $class['name'] = $_POST['class_name'];

        // Delete the existing sections
        $sections = $class->sections;

        $query = [];

        foreach (Request::body('section_id') as $section)
            array_push($query, [
                'class_id' => $id,
                'section_id' => $section
            ]);

        foreach ($sections as $section)
            ClassesSectionModel::where('class_id', $id)->where('section_id', $section['section_id'])->delete();

        ClassesSectionModel::insert($query);

        $class->save();

        return redirect("classes", [
            'type' => 'success',
            'message' => "Record updated successfully"
        ]);
    }

    public function delete($id)
    {
        try {
            $deleted = ClassesModel::where('class_id', $id)->delete();

            // Delete all the relations
            ClassesSectionModel::where('class_id', $id)->delete();

            redirect($this->route, [
                'type' => 'success',
                'message' => "Record Deleted successfully"
            ]);
        }catch (Exception $ex){
            throw new AppError($this->route, "You can't perform this action as the other data depends on this.", 23000);
        }

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
        $subjects = ClassesModel::where('class_id', $class_id)->first();
        if($subjects){
            $subjects = $subjects->subjects;

        }
        if (!$subjects)
            throw new ApiError('No Record Found', 404);

        echo json_encode($subjects);
    }
}
