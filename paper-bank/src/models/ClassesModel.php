<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ClassesModel extends Eloquent
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  public $timestamps = false;

  protected $table = "sms_classes";
  protected $primaryKey = "class_id";
  protected $guarded = ['class_id'];
  const DELETED_AT = 'date_deleted';

  use \Illuminate\Database\Eloquent\SoftDeletes;

  public function sections()
  {
    return $this->belongsToMany(
      SectionModel::class,
      'classes_has_sections',
      'class_id',
      'section_id'
    );
  }

  public function subjects()
  {
    return $this->hasMany(SubjectModel::class, 'id_class', 'class_id');
  }

  public function students(){
    return $this->hasMany(\src\model\StudentModel::class, 'class_id', 'class_id');
  }

  public static function activeClasses() {
    return self::where('class_status','=',1)
    ->where('is_deleted','!=',1)
    ->get();
  }

}
