<?php

class Sections extends Controller
{
    public function __construct()
    {
        $this->authMiddleware();
    }

    protected $route = "sections";

    public function index()
    {
        $sections = SectionModel::all();

        $data = [
            'primary_key' => "section_id",
            'table_header' => ["Section Name"],
            'controller' => "sections",
            "data" => $sections,
            "show" => ['section_name']
        ];

        $this->view("sections/index", $data);
    }

    public function edit($id)
    {
        $section = SectionModel::where('section_id', $id)->first();

        if(!$section)
            throw new AppError($this->route, 'No record found with the given ID', 404);

        $data = [
            'section_name' => $section['section_name'],
            'id' => $id
        ];

        $this->view("sections/edit", $data);
    }

    public function update($id)
    {
        $section = SectionModel::where('section_id', $id)->first();

        $section['section_name'] = $_POST['section_name'];

        $isSaved = $section->save();

        if (!$isSaved)
            throw new AppError($this->route, 'Error updating the record', 500);

        return redirect("sections", [
            'type' => 'success',
            'message' => 'Record updated successfully'
        ]);
    }

    public function add()
    {
        $this->view("sections/create");
    }

    public function create()
    {
        $section = SectionModel::create([
            'section_name' => $_POST['section_name']
        ]);

        if (!$section)
            throw new AppError($this->route, 'Error creating new section', 400);

        return redirect("sections", [
            'type' => 'success',
            'message' => "Record created successfully"
        ]);
    }

    public function delete($id)
    {
        try {
            $isDeleted = SectionModel::where('section_id', $id)->delete();

            return redirect("sections", [
                'type' => 'success',
                'message' => 'Record deleted successfully'
            ]);
        } catch (Exception $ex){
            throw new AppError($this->route, 'You cannot delete this record as other record depends on it.', 23000);
        }

    }
}
