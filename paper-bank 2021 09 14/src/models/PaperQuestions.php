<?php

class PaperQuestions extends Illuminate\Database\Eloquent\Model{

    protected $guarded = ['id'];
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = "sms_pb_paper_questions";

    use \Illuminate\Database\Eloquent\SoftDeletes;
    const DELETED_AT = 'date_deleted';

    public function question()
    {
        return $this->hasOne(Question::class, 'question_id', 'id_question');
    }

    public function paper(){
        return $this->hasOne(PaperModel::class, 'paper_id', 'id_paper');
    }
}