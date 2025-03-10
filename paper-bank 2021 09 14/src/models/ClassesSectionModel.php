<?php
use Illuminate\Database\Eloquent\Model;

class ClassesSectionModel extends Model{
    protected $fillable = ['class_id', 'section_id'];
    public $timestamps = false;
    protected $table = "sms_pb_classes_has_sections";

}