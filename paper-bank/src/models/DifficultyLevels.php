<?php

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{

  protected $fillable = ['difficulty_level'];

  public $timestamps = false;
  protected $primaryKey = 'id';
  protected $table = "sms_pb_question_difficulty_levels";
}
