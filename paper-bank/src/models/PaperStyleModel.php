<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class PaperStyle extends Eloquent
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  public $timestamps = false;

  protected $table = "sms_pb_paper_style";
  protected $primaryKey = "paper_style_id";
  protected $guarded = ['paper_style_id'];
  const DELETED_AT = 'date_deleted';  

  use \Illuminate\Database\Eloquent\SoftDeletes;

}