<?php

use Illuminate\Database\Eloquent\Model;

class ResultItemModel extends Model
{
  protected $table = "sms_pb_result_items";
  protected $fillable = [
    'id_result', 'id_subject', 'obtained_marks'
  ];

  public $timestamps = false;

  public function subject()
  {
    return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }
}
