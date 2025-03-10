<?php

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class TopicModel extends Model{
  protected $table = "sms_pb_topics";
  protected $primaryKey = 'topic_id';
  public $timestamps = false;
  protected $guarded = ['topic_id'];

  const DELETED_AT = 'date_deleted';
  use SoftDeletes;

  public function subject(){
    return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }

  public function class()
  {
    return $this->hasOne(ClassesModel::class, 'class_id', 'id_class');
  }

  public function chapter()
  {
    return $this->hasOne(Chapter::class, 'chapter_id', 'id_chapter');
  }
}