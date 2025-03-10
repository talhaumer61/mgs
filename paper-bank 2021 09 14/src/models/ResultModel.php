<?php

use Illuminate\Database\Eloquent\Model;

class ResultModel extends Model
{
  protected $table = "sms_pb_results";
  protected $fillable = [
    'id_paper_term', 'id_student', 'id_class'
  ];

  protected $primaryKey = 'result_id';

  public $timestamps = false;

  public function resultItems()
  {
    return $this->hasMany(ResultItemModel::class, 'id_result', 'result_id');
  }
}
