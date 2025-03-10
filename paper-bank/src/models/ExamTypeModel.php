<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ExamTypeModel extends Eloquent
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  public $timestamps = false;

  protected $table = "sms_examtypes";
  protected $primaryKey = "type_id";
  protected $guarded = ['type_id'];
  const DELETED_AT = 'date_deleted';

  use \Illuminate\Database\Eloquent\SoftDeletes;

}
