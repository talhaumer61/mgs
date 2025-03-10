<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Question extends Eloquent
{

  public $timestamps = false;

  protected $table = "sms_pb_questions";
  protected $primaryKey = "question_id";
  protected $guarded = ['question_id'];
  const DELETED_AT = 'date_deleted';

  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function class()
  {
    return $this->hasOne(ClassesModel::class, 'class_id', 'id_class');
  }

  public function subject()
  {
    return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }

  public function chapter()
  {
    return $this->hasOne(Chapter::class, 'chapter_id', 'id_chapter');
  }

  public function answers_options()
  {
    return $this->hasOne(ObjectiveAnswers::class, 'id_question', 'question_id');
  }

  // public function difficulty()
  // {
  //   return $this->hasMany(DifficultyLevel::class, 'id', 'difficulty_level');
  // }
}
