<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SectionModel extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    //  Overwrite the defaul table name for the model   
    public $timestamps = false;

    protected $table = "sms_pb_sections";
    protected $primaryKey = "section_id";
    protected $fillable = [
        'section_name'
    ];
}
