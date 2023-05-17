<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\SurveyQuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'image_url'=>$this->image ? URL::to($this->image) : null,
            'slug'=>$this->slug,
            'status'=>$this->status,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'expiry_date'=>$this->expiry_date,
            'questions'=>SurveyQuestionResource::collection($this->questions)

        ];
    }
}
