<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ObjectiveAnswers extends Eloquent
{
  public $timestamps = false;

  protected $table = "sms_pb_objective_answers";
  protected $primaryKey = "answer_id";
  protected $guarded = ['answer_id'];
}
