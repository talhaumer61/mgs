<?php

class UserModel extends \Illuminate\Database\Eloquent\Model{
    protected $table = "sms_pb_users";
    protected $primaryKey = "user_id";
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'status', 'employee_id'
    ];
}