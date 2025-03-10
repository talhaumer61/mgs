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
     $long_question = Question::where('id_question_type', '1')->get()->count();
     $short_question = Question::where('id_question_type', '2')->get()->count();
     $objective_questions = Question::where('id_question_type', '3')->get()->count();

     $subjective_questions = $long_question + $objective_questions;

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
