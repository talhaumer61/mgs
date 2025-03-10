<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SubjectModel extends Eloquent
{
  public $timestamps = false;
  protected $table = "sms_classsubjects";
  protected $primaryKey = "subject_id";

  public function class()
  {
    return $this->hasOne(ClassesModel::class, 'class_id', 'id_class');
  }

  public function chapters()
  {
    return $this->hasMany(Chapter::class, 'id_subject', 'subject_id');
  }
}
