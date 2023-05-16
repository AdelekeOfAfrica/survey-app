<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory, Sluggable;
    protected $guarded=[];
    protected $table="surveys";
    protected $fillable=[];

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


}


