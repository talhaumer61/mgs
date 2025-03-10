<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaperModel extends Model
{
  use SoftDeletes;
  const DELETED_AT = 'date_deleted';

  public $timestamps = false;
  protected $primaryKey = 'paper_id';
  protected $table = "sms_pb_papers";
  protected $guarded = ['paper_id'];

  public function questions()
  {
    return $this->hasMany(PaperQuestions::class, 'id_paper', 'paper_id');
  }

  public function class()
  {
    return $this->hasOne(ClassesModel::class, 'class_id', 'id_class');
  }

  public function chapter_from()
  {
    return $this->hasOne(Chapter::class, 'chapter_id', 'id_chapter_from');
  }

  public function chapter_to()
  {
    return $this->hasOne(Chapter::class, 'chapter_id', 'id_chapter_to');
  }

  public function subject()
  {
    return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }

  public function exam_type()
  {
   return $this->hasOne(ExamTypeModel::class, 'type_id', 'id_exam_type');
  }

}
