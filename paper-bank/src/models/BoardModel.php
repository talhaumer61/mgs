<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Database\Eloquent\SoftDeletes;

class BoardModel extends Eloquent
{
  public $timestamps = false;
  protected $table = "sms_pb_boards";
  protected $primaryKey = "board_id";
  protected $guarded = ['board_id'];
  const DELETED_AT = 'date_deleted';

  use SoftDeletes;
}