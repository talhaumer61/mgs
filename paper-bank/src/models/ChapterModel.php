<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Chapter extends Eloquent
{
  public $timestamps = false;

  protected $table = "sms_pb_chapters";
  protected $primaryKey = "chapter_id";
  protected $guarded = ['chapter_id'];

  public function subject()
  {
    return $this->hasOne(SubjectModel::class, 'subject_id', 'id_subject');
  }

  public function topics(){
    return $this->hasMany(TopicModel::class, 'id_chapter', 'chapter_id');
  }
}
