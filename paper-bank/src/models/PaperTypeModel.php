<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class PaperType extends Eloquent
{
  public $timestamps = false;

  protected $table = "sms_examtypes";
  protected $primaryKey = "type_id";
  protected $guarded = ['type_id'];
//  protected $fillable = [
//    'total_marks','id_deleted','type_id', 'type_status', 'type_term', 'type_name', 'total_marks', 'pass_marks', 'type_details', 'type_ordering', 'is_deleted', 'id_added', 'id_modify', 'id_deleted', 'ip_deleted', 'date_added', 'date_modify', 'date_deleted'
//  ];

}
