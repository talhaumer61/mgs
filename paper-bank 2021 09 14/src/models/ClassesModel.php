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
    protected $fillable = [
      'class_id', 'status_code', 'class_code', 'class_name', 'id_campus', 'is_deleted', 'id_deleted', 'ip_deleted', 'date_deleted'
    ];

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
}
