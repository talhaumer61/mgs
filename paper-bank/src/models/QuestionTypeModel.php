<?php

class QuestionTypeModel extends \Illuminate\Database\Eloquent\Model{

  public $timestamps = false;
  protected $table = "sms_pb_question_type";
  protected $primaryKey = "question_type_id";
  protected $guarded = ['question_type_id'];

  use \Illuminate\Database\Eloquent\SoftDeletes;
  const DELETED_AT = 'date_deleted';

  public static function activeTypes(){
    return self::where('question_type_status', true)->get();
  }
}