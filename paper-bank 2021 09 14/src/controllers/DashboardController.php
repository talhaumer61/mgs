<?php

class Dashboard extends Controller
{
    public function __construct()
    {
        $this->authMiddleware();
    }

    public function index()
    {
      $total_papers = PaperModel::all()->count();
      $subjective_questions = Question::where('is_objective', false)->get()->count();
      $objective_questions = Question::where('is_objective', true)->get()->count();

      $data = [
        'total_papers' => $total_papers,
        'objective_questions' => $objective_questions,
        'subjective_questions' => $subjective_questions
      ];

      $this->view("dashboard/index", $data);
    }

    public function demo()
    {
        $this->view("dashboard/demo");
    }
}
