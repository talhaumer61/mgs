<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SubjectModel extends Eloquent
{
  public $timestamps = false;
  protected $table = "sms_classsubjects";
  protected $primaryKey = "subject_id";
  protected $guarded = ['subject_id'];
  const DELETED_AT = 'date_deleted';

  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function class()
  {
    return $this->hasOne(ClassesModel::class, 'class_id', 'id_class');
  }

  public function chapters()
  {
    return $this->hasMany(Chapter::class, 'id_subject', 'subject_id');
  }

  public static function activeSubjects(){
    return self::where('subject_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();
  }

  public static function notDeletedSubjects(){
    return self::where('is_deleted','!=',1)->get();
  }
}
