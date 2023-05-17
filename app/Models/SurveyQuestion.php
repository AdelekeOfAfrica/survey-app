<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'type', 'question', 'description', 'data', 'survey_id'];

    public function surveys(){
        return $this->belongsTo(Survey::class); //this means that many question belongs to a survey
    }
}
