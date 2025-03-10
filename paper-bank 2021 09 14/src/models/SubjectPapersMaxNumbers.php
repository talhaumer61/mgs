<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectPapersMaxNumbers extends Eloquent
{
  use SoftDeletes;
  const DELETED_AT = 'date_deleted';
  public $timestamps = false;

  protected $table = "sms_pb_subject_papers_max_numbers";
  protected $primaryKey = "id";
  protected $guarded = ['id'];


  public function subject()
  {
      return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }

  public function exam()
  {
    return $this->hasOne(PaperType::class, 'type_id', 'id_exam_type');
  }
}
