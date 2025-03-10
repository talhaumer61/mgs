<?php

class PaperStyles extends Controller {

  protected $route = "paperstyles";

  public function index()
  {

    $styles = PaperStyle::all();
    $data = [
      'styles' => $styles,
      'title' => "Paper Style"
    ];

    return $this->view("paperstyle/index", $data);
    
  }
}