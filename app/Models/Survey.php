<?php

namespace App\Models;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = ['user_id', 'image', 'title', 'slug', 'status', 'description', 'expire_date'];
    protected $table="surveys";
    

    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX ='checkbox';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName(){
        return 'id'; //you can change it to slug if you want to be getting your data through slug
    }

    public function questions(){
        return $this->hasMany(SurveyQuestion::class); //this means it a survey has many questions
    }

    public function answers(){
        return $this->hasMany(SurveyAnswer::class); // this means that a survey has many answers
    }


}




