<?php

class PublisherModel extends  \Illuminate\Database\Eloquent\Model {
  use \Illuminate\Database\Eloquent\SoftDeletes;

  public $timestamps = false;
  const DELETED_AT = "date_deleted";
  protected $table = "sms_pb_publishers";
  protected $primaryKey = "publisher_id";
  protected $guarded = ["publisher_id"];

}