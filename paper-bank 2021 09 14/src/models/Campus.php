<?php

class Campus extends \Illuminate\Database\Eloquent\Model{
  protected $fillable = [
    'campus_id', 'campus_name', 'campus_address', 'status'
  ];

  protected $table = "sms_pb_campus";
  public $timestamps = false;
  protected $primaryKey = "campus_id";
}